<?php
class TableEntity {
	private $_arrFields    = [];
    private $_tableName    = '';
    private $_primaryKey   = '';
    private $_orderByField = '';
	private $_ci;

	public function __construct($arrParam) {
		$tableName  = $arrParam['tableName'] ?? '';
		$primaryKey = $arrParam['primaryKey'] ?? '';
		$orderBy    = $arrParam['orderBy'] ?? '';

		$this->_tableName    = $tableName;
		$this->_primaryKey   = $primaryKey;
		$this->_orderByField = $orderBy;

		$this->_ci =& get_instance();
	}

	public function getConn() {
        $this->_ci->load->database();
		$this->_ci->db->db_debug = FALSE;
        return $this->_ci->db;
    }

	public function addField($name, $type, $default) {
        $this->_arrFields[] = array(
            "name"    => $name,
            "type"    => $type,
            "default" => $default,
        );
    }

	private function getStrFieldsName() {
        $arrFieldName = [];
        foreach ($this->_arrFields as $field) {
            $arrFieldName[] = $field["name"];
        }

        return implode(",", $arrFieldName);
    }

	private function getFieldType($fieldName) {
        foreach ($this->_arrFields as $field) {
            if ($field["name"] === $fieldName) {
                return $field["type"];
            }
        }

        return false;
    }

	private function getDbErrorString($conn) {
		$code = $conn->error()["code"] ?? 0;
		$msg  = $conn->error()["message"] ?? 0;

		return "[$code] $msg";
	}

	private function fReturn($error, $message, $arrRet=[]) {
		return array(
			"error"   => $error,
			"message" => $message,
			"arrRet"  => $arrRet
		);
	}

	private function escapeField($fldValue) {
		$conn = $this->getConn();
		return $conn->escape($fldValue);
	}

	public function query($arrFilters) {
        $strFieldsName = $this->getStrFieldsName();
        $tableName     = $this->_tableName;
        $orderBy       = $this->_orderByField;

        $sql  = " SELECT $strFieldsName FROM $tableName WHERE TRUE ";
        foreach ($arrFilters as $fieldName => $fieldValue) {
            if ($fieldValue !== NULL) {
                $fieldType  = $this->getFieldType($fieldName);

                $strWhere  = "";
                if ($fieldType === "string") {
					$fieldValue = $this->escapeField(utf8_decode("%$fieldValue%"));
                    $strWhere   = " AND $fieldName LIKE $fieldValue";
                } else {
					$fieldValue = $this->escapeField(utf8_decode($fieldValue));
                    $strWhere   = " AND $fieldName = $fieldValue";
                }

                $sql .= $strWhere;
            }
        }
        $sql .= " ORDER BY $orderBy ";

        try {
            $conn  = $this->getConn();
			$query = $conn->query($sql);
            if (!$query) {
				return $this->fReturn(true, 'Erro na consulta. Msg: ' .  $this->getDbErrorString($conn));
            } else {
				$arrResult  = $query->result_array();
                $returnRows = $this->queryArrRet($arrResult);
				return $this->fReturn(false, 'Consulta executada!', $returnRows);
            }
        } catch (Exception $e) {
            // Do your error handling here
            // $message = $e->getMessage();
            return $this->fReturn(true, 'Erro ao executar a consulta. Msg: ' .  $this->getDbErrorString($conn));
        }
    }

	private function queryArrRet($rows) {
        $arrRet = [];

        foreach ($rows as $row) {
            $arrLine = [];
            for ($i = 0; $i < count($this->_arrFields); $i++) {
                $field      = $this->_arrFields[$i];
                $fieldName  = $field["name"] ?? "";
                $fieldType  = $field["type"] ?? "";
                $fieldValue = $row[$fieldName] ?? "";

                # if ($fieldType === 'string') {
                #     $fieldValue = utf8_decode($fieldValue);
                # }

                $arrLine[$fieldName] = $fieldValue;
            }

            $arrRet[] = $arrLine;
        }

        return (count($arrRet) === 1) ? $arrRet[0]: $arrRet;
    }

	public function fGet($id){
        return $this->query(array($this->_primaryKey => $id)) ?? [];
    }

	public function fPost($arrNamesValues) {
        $request       = $arrNamesValues;
        $arrFieldName  = [];
        $arrFieldValue = [];

        foreach ($this->_arrFields as $field) {
            $fieldName   = $field["name"];
            $fieldValue  = (isset($request[$fieldName])) ? $request[$fieldName]: $field["default"];

            $canAddField = ($fieldName !== $this->_primaryKey) || ($fieldName === $this->_primaryKey && $fieldValue > 0);
            if($canAddField){
                if ($fieldValue !== 'NULL') {
                    $fieldValue = $this->escapeField(utf8_decode($fieldValue));
                }

                $arrFieldName[]  = $fieldName;
                $arrFieldValue[] = $fieldValue;
            }
        }

        $tableName   = $this->_tableName;
        $tableFields = implode(",", $arrFieldName);
        $tableValues = implode(",", $arrFieldValue);
        $sql         = "INSERT INTO $tableName ($tableFields) VALUES ($tableValues)";

		try {
            $conn  = $this->getConn();
			$query = $conn->query($sql);
            if (!$query) {
				return $this->fReturn(true, 'Erro no post. Msg: ' . $this->getDbErrorString($conn));
            } else {
				$insertId = $conn->insert_id();
                $ret      = $this->fGet($insertId);
				$retRows  = $ret["arrRet"] ?? [];
				return $this->fReturn(false, 'Post executado!', $retRows);
            }
        } catch (Exception $e) {
            // Do your error handling here
            // $message = $e->getMessage();
            return $this->fReturn(true, 'Erro ao executar o post. Msg: ' .  $this->getDbErrorString($conn));
        }
    }

	public function fPut($arrNamesValues) {
        $request         = $arrNamesValues;
        $primaryKey      = $this->_primaryKey;
        $primaryKeyValue = $request[$primaryKey] ?? "";
        $arrUpdateFields = [];

        foreach ($this->_arrFields as $field) {
            $fieldName = $field["name"];
            if (isset($request[$fieldName]) && $fieldName !== $primaryKey) {
                $fieldValue = $request[$fieldName];

                if($fieldValue === NULL){
                    $fieldValue = 'NULL';
                } else {
                    $fieldValue = $this->escapeField(utf8_decode($fieldValue));
                }

                $arrUpdateFields[] = " $fieldName = $fieldValue ";
            }
        }

        $tableName   = $this->_tableName;
        $strFields   = implode(",", $arrUpdateFields);
        $sql         = "UPDATE $tableName SET $strFields WHERE $primaryKey = $primaryKeyValue";

        # echo "<pre>";
        # echo $sql;
        # echo "</pre>";
        # die;

		try {
            $conn  = $this->getConn();
			$query = $conn->query($sql);
            if (!$query) {
				return $this->fReturn(true, 'Erro no put. Msg: ' . $this->getDbErrorString($conn));
            } else {
                $ret      = $this->fGet($primaryKeyValue);
				$retRows  = $ret["arrRet"] ?? [];
				return $this->fReturn(false, 'Put executado!', $retRows);
            }
        } catch (Exception $e) {
            // Do your error handling here
            // $message = $e->getMessage();
            return $this->fReturn(true, 'Erro ao executar o put. Msg: ' .  $this->getDbErrorString($conn));
        }
    }

	public function fDelete($id) {
        $primaryKey = $this->_primaryKey;
        $tableName  = $this->_tableName;

        $sql  = "DELETE FROM $tableName WHERE $primaryKey = $id";

        try {
            $conn  = $this->getConn();
			$query = $conn->query($sql);
            if (!$query) {
				return $this->fReturn(true, 'Erro no delete. Msg: ' . $this->getDbErrorString($conn));
            } else {
				return $this->fReturn(false, 'Delete executado!');
            }
        } catch (Exception $e) {
            // Do your error handling here
            // $message = $e->getMessage();
            return $this->fReturn(true, 'Erro ao executar o delete. Msg: ' .  $this->getDbErrorString($conn));
        }
    }
}
