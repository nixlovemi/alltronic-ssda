START TRANSACTION;

CREATE TABLE `tb_usuario` IF NOT EXISTS (
  `usu_id` int(11) NOT NULL,
  `usu_login` varchar(40) NOT NULL,
  `usu_senha` varchar(50) NOT NULL COMMENT 'md5',
  `usu_nome` varchar(60) NOT NULL,
  `usu_nivel` int(11) NOT NULL DEFAULT 0 COMMENT 'nível de permissão de acesso; 100/90/80/70/60/50/40/30/20/10/0',
  `usu_ativo` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tb_usuario` (`usu_id`, `usu_login`, `usu_senha`, `usu_nome`, `usu_nivel`, `usu_ativo`) VALUES
(1, 'alltronic', 'f4688fd43cbef7ac061c7309a475519f', 'Alltronic', 100, b'1');

ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`usu_id`),
  ADD UNIQUE KEY `idx_usu_login` (`usu_login`);

ALTER TABLE `tb_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `alltronic_ssda`.`tb_usuario` DROP INDEX `idx_usu_login`, ADD UNIQUE `idx_usuario_login` (`usu_login`) USING BTREE;

ALTER TABLE `tb_maquina` CHANGE `maq_ativo` `maq_ativo` TINYINT(1) NOT NULL DEFAULT '1';

ALTER TABLE `tb_maquina_grupo` CHANGE `mgr_ativo` `mgr_ativo` TINYINT(1) NOT NULL DEFAULT '1';

ALTER TABLE `tb_tipo_status` CHANGE `tst_ativo` `tst_ativo` TINYINT(1) NOT NULL DEFAULT '1';

ALTER TABLE `tb_usuario` CHANGE `usu_ativo` `usu_ativo` TINYINT(1) NOT NULL DEFAULT '1';

ALTER TABLE tb_usuario ADD CONSTRAINT check_usuario_ativo CHECK (usu_ativo IN (0, 1));

ALTER TABLE tb_usuario ADD CONSTRAINT check_usuario_nivel CHECK (usu_nivel IN (0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100));

ALTER TABLE tb_tipo_status ADD CONSTRAINT check_tipo_status_ativo CHECK (tst_ativo IN (0, 1));

ALTER TABLE tb_status ADD CONSTRAINT check_status_bit CHECK (sta_bit >= 0);

ALTER TABLE tb_maquina_grupo ADD CONSTRAINT check_maq_grupo_ativo CHECK (mgr_ativo IN (0, 1));

ALTER TABLE tb_maquina ADD CONSTRAINT check_maquina_ativo CHECK (maq_ativo IN (0, 1));

ALTER TABLE tb_log_status_maquina ADD CONSTRAINT check_log_sta_maquina_valor CHECK (lsm_valor IN (0, 1));

ALTER TABLE tb_log_envase_bico ADD CONSTRAINT check_log_envase_bico CHECK (leb_valor >= 0);

CREATE TABLE `alltronic_ssda`.`tb_menu` ( `men_id` INT NOT NULL AUTO_INCREMENT , `men_descricao` VARCHAR(30) NOT NULL , `men_pai` INT NULL , `men_icon` VARCHAR(60) NULL , `men_nivel` INT NOT NULL COMMENT 'nível de acesso/permissão' , `men_ativo` TINYINT NOT NULL DEFAULT '1' , PRIMARY KEY (`men_id`)) ENGINE = InnoDB;

ALTER TABLE tb_menu ADD CONSTRAINT check_menu_ativo CHECK (men_ativo IN (0, 1));

ALTER TABLE tb_menu ADD CONSTRAINT check_menu_nivel CHECK (men_nivel IN (0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100));

ALTER TABLE `tb_menu` ADD `men_controller` VARCHAR(40) NOT NULL AFTER `men_pai`, ADD `men_action` VARCHAR(40) NOT NULL AFTER `men_controller`;

ALTER TABLE `tb_menu` CHANGE `men_controller` `men_controller` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `tb_menu` CHANGE `men_action` `men_action` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

INSERT INTO `tb_menu` (`men_id`, `men_descricao`, `men_pai`, `men_controller`, `men_action`, `men_icon`, `men_nivel`, `men_ativo`) VALUES (NULL, 'Cadastros', NULL, NULL, NULL, '<i class=\"far fa-fw fa-folder\"></i>', '100', '1');

INSERT INTO `tb_menu` (`men_id`, `men_descricao`, `men_pai`, `men_controller`, `men_action`, `men_icon`, `men_nivel`, `men_ativo`) VALUES (NULL, 'Menu', (SELECT men_id FROM tb_menu WHERE men_descricao = 'Cadastros'), 'Menu', 'index', '<i class=\"fas fa-fw fa-bars\"></i>', '100', '1');

ALTER TABLE `tb_menu` CHANGE `men_nivel` `men_nivel` INT(11) NOT NULL DEFAULT '0' COMMENT 'nível de acesso/permissão';

INSERT INTO `tb_menu` (`men_id`, `men_descricao`, `men_pai`, `men_controller`, `men_action`, `men_icon`, `men_nivel`, `men_ativo`) VALUES (NULL, 'Relatórios', NULL, NULL, NULL, '<i class=\"fas fa-print\"></i>', '0', '1');

UPDATE `tb_menu` SET men_controller = 'Relatorio', men_action = 'index' WHERE men_descricao = 'Relatórios';

DROP TRIGGER IF EXISTS trig_check_menu_insert;
delimiter //
CREATE TRIGGER `trig_check_menu_insert` BEFORE INSERT ON `tb_menu` FOR EACH ROW 
BEGIN
	DECLARE v_msg_error TEXT DEFAULT '';
    DECLARE v_temp_string TEXT DEFAULT '';
	IF (LENGTH(COALESCE(NEW.men_descricao, '')) < 3) THEN
    	SET v_temp_string = v_msg_error;
        SET v_msg_error   = CONCAT(' - A descrição deve ter pelo menos 3 caracteres! ', v_temp_string);
	END IF;
    
	IF (LENGTH(COALESCE(NEW.men_controller, '')) > 0) AND (LENGTH(COALESCE(NEW.men_action, '')) <= 0) THEN
    	SET v_temp_string = v_msg_error;
        SET v_msg_error   = CONCAT(' - Preencha a action! ', v_temp_string);
	END IF;
    
    IF (LENGTH(COALESCE(NEW.men_action, '')) > 0) AND (LENGTH(COALESCE(NEW.men_controller, '')) <= 0) THEN
    	SET v_temp_string = v_msg_error;
        SET v_msg_error   = CONCAT(' - Preencha o controller! ', v_temp_string);
	END IF;
    
    IF v_msg_error != '' THEN
    	SET v_temp_string = CONCAT('Corrija os erros antes de prosseguir: ', v_msg_error);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_temp_string;
	END IF;
END //
delimiter ;

DROP TRIGGER IF EXISTS trig_check_menu_update;
delimiter //
CREATE TRIGGER `trig_check_menu_update` BEFORE INSERT ON `tb_menu` FOR EACH ROW 
BEGIN
	DECLARE v_msg_error TEXT DEFAULT '';
    DECLARE v_temp_string TEXT DEFAULT '';
	IF (LENGTH(COALESCE(NEW.men_descricao, '')) < 3) THEN
    	SET v_temp_string = v_msg_error;
        SET v_msg_error   = CONCAT(' - A descrição deve ter pelo menos 3 caracteres! ', v_temp_string);
	END IF;
    
	IF (LENGTH(COALESCE(NEW.men_controller, '')) > 0) AND (LENGTH(COALESCE(NEW.men_action, '')) <= 0) THEN
    	SET v_temp_string = v_msg_error;
        SET v_msg_error   = CONCAT(' - Preencha a action! ', v_temp_string);
	END IF;
    
    IF (LENGTH(COALESCE(NEW.men_action, '')) > 0) AND (LENGTH(COALESCE(NEW.men_controller, '')) <= 0) THEN
    	SET v_temp_string = v_msg_error;
        SET v_msg_error   = CONCAT(' - Preencha o controller! ', v_temp_string);
	END IF;
    
    IF v_msg_error != '' THEN
    	SET v_temp_string = CONCAT('Corrija os erros antes de prosseguir: ', v_msg_error);
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_temp_string;
	END IF;
END //
delimiter ;

COMMIT;
