CREATE TABLE `h_account` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint DEFAULT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_598877E1CDA0B9_00568489` (`client_id`),
  KEY `FK_598877E1CDA365_40291314` (`data_client_id`),
  KEY `FK_598877E1CDA561_88661674` (`entity_id`),
  KEY `FK_598877E1CDA757_49864533` (`dataentity_id`),
  KEY `IDX_598877E1D374B5_31155582` (`code`) USING BTREE,
  KEY `IDX_598877E1D37779_00820538` (`description`) USING BTREE,
  CONSTRAINT `FK_598877E1CDA0B9_00568489` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598877E1CDA365_40291314` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598877E1CDA561_88661674` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_598877E1CDA757_49864533` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_accountnotificationoptin` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6479E8B47F38C3_87190149` (`code`) USING BTREE,
  KEY `IDX_6479E8B47F3AF5_09716160` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_apicall` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67E3C4AE216306_94739696` (`code`) USING BTREE,
  KEY `IDX_67E3C4AE2163E6_36765154` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_apicalltype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67E3C48EC976B7_65584193` (`code`) USING BTREE,
  KEY `IDX_67E3C48EC977B2_69878601` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_apikey` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67E379C9A17F63_22376572` (`code`) USING BTREE,
  KEY `IDX_67E379C9A18051_96519248` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_application` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5A180AD995C332_40264333` (`client_id`),
  KEY `FK_5A180AD995C330_71284869` (`data_client_id`),
  KEY `FK_5A180AD995C337_46354387` (`entity_id`),
  KEY `FK_5A180AD995C335_11238564` (`dataentity_id`),
  KEY `IDX_5A180AD9989FA1_43392076` (`code`) USING BTREE,
  KEY `IDX_5A180AD9989FA7_77984188` (`description`) USING BTREE,
  CONSTRAINT `FK_5A180AD995C330_71284869` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5A180AD995C332_40264333` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5A180AD995C335_11238564` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5A180AD995C337_46354387` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_applicationmodule` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D95BF82761898_87438881` (`client_id`),
  KEY `FK_5D95BF82761C23_88920349` (`data_client_id`),
  KEY `FK_5D95BF82761E36_30393095` (`entity_id`),
  KEY `FK_5D95BF82762032_56647709` (`dataentity_id`),
  KEY `IDX_5D95BF827D7432_42594676` (`code`) USING BTREE,
  KEY `IDX_5D95BF827D76D3_49821829` (`description`) USING BTREE,
  CONSTRAINT `FK_5D95BF82761898_87438881` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D95BF82761C23_88920349` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D95BF82761E36_30393095` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D95BF82762032_56647709` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_applicationmodule_module` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D95C33E6FDE19_35018072` (`client_id`),
  KEY `FK_5D95C33E6FE415_90495904` (`data_client_id`),
  KEY `FK_5D95C33E6FE7A9_87202139` (`entity_id`),
  KEY `FK_5D95C33E6FEB06_56612741` (`dataentity_id`),
  KEY `IDX_5D95C33E7769B6_43970346` (`code`) USING BTREE,
  KEY `IDX_5D95C33E776C57_66946239` (`description`) USING BTREE,
  CONSTRAINT `FK_5D95C33E6FDE19_35018072` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D95C33E6FE415_90495904` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D95C33E6FE7A9_87202139` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D95C33E6FEB06_56612741` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_authenticationplugin` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_646C8ED898E2D5_09999453` (`code`) USING BTREE,
  KEY `IDX_646C8ED898E3C0_03914593` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_authenticationtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6048A96FC18E66_49643151` (`code`) USING BTREE,
  KEY `IDX_6048A96FC19023_16734524` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_batchjob` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5988784063B344_06814197` (`client_id`),
  KEY `FK_5988784063B5E4_73509767` (`data_client_id`),
  KEY `FK_5988784063B7E0_45924724` (`entity_id`),
  KEY `FK_5988784063B9C8_47190066` (`dataentity_id`),
  KEY `IDX_59887840681C42_61444316` (`code`) USING BTREE,
  KEY `IDX_59887840681EE0_74299470` (`description`) USING BTREE,
  CONSTRAINT `FK_5988784063B344_06814197` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988784063B5E4_73509767` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988784063B7E0_45924724` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5988784063B9C8_47190066` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_branch` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_66595482418152_14188897` (`code`) USING BTREE,
  KEY `IDX_66595482418220_02924723` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_branch_user` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6659554F19A467_53144138` (`code`) USING BTREE,
  KEY `IDX_6659554F19A532_64365668` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_chart` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_68664E6BC23D27_72835098` (`code`) USING BTREE,
  KEY `IDX_68664E6BC23E52_38796219` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_charttype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_68664E60781DF4_57126163` (`code`) USING BTREE,
  KEY `IDX_68664E60781EE7_86539743` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_checkdigittype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6528B8C51813D3_15832620` (`code`) USING BTREE,
  KEY `IDX_6528B8C51818A1_42427226` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_client` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint DEFAULT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229C6444B846_17937514` (`client_id`),
  KEY `FK_59229C6444BA67_21813371` (`data_client_id`),
  KEY `FK_59229C6444BC54_00458764` (`entity_id`),
  KEY `FK_59229C6444BE43_42799533` (`dataentity_id`),
  KEY `IDX_59229C644A0DA1_04073824` (`code`) USING BTREE,
  KEY `IDX_59229C644A1036_20949067` (`description`) USING BTREE,
  CONSTRAINT `FK_59229C6444B846_17937514` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C6444BA67_21813371` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C6444BC54_00458764` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229C6444BE43_42799533` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_clientdashboard_chart` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_68664F3FC6AF11_27641764` (`code`) USING BTREE,
  KEY `IDX_68664F3FC6AFE7_13992093` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_clientproduct` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BD0E4331D6_87172272` (`code`) USING BTREE,
  KEY `IDX_5F02BD0E433455_81339178` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_clientsetting` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF5CCE8A7A320_47351314` (`code`) USING BTREE,
  KEY `IDX_5EF5CCE8A7A5A5_95853855` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_cms` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5ED72F9373F854_08746772` (`code`) USING BTREE,
  KEY `IDX_5ED72F9373FB55_32602110` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_colour` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_605077C9B1BAF5_53868520` (`code`) USING BTREE,
  KEY `IDX_605077C9B1BCA3_31814828` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_config` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5DA6F427315AE4_22132864` (`client_id`),
  KEY `FK_5DA6F427315D42_20689342` (`data_client_id`),
  KEY `FK_5DA6F427315F10_10985061` (`entity_id`),
  KEY `FK_5DA6F4273160D1_56870776` (`dataentity_id`),
  KEY `IDX_5DA6F42739E764_64617905` (`code`) USING BTREE,
  KEY `IDX_5DA6F42739ED60_33849504` (`description`) USING BTREE,
  CONSTRAINT `FK_5DA6F427315AE4_22132864` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5DA6F427315D42_20689342` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5DA6F427315F10_10985061` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5DA6F4273160D1_56870776` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_configtask` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5DA7F7A26B7F22_02809855` (`client_id`),
  KEY `FK_5DA7F7A26B82F6_04692204` (`data_client_id`),
  KEY `FK_5DA7F7A26B8516_90893439` (`entity_id`),
  KEY `FK_5DA7F7A26B8729_51502920` (`dataentity_id`),
  KEY `IDX_5DA7F7A274F2D5_95974238` (`code`) USING BTREE,
  KEY `IDX_5DA7F7A274F613_19742753` (`description`) USING BTREE,
  CONSTRAINT `FK_5DA7F7A26B7F22_02809855` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5DA7F7A26B82F6_04692204` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5DA7F7A26B8516_90893439` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5DA7F7A26B8729_51502920` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_contact` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_66FAC38CC63605_98481985` (`code`) USING BTREE,
  KEY `IDX_66FAC38CC63703_53126275` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_contactstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_66FAC49CA7D2C9_13784812` (`code`) USING BTREE,
  KEY `IDX_66FAC49CA7D3B5_91174592` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_contacttype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_66FACAE4958E03_88919499` (`code`) USING BTREE,
  KEY `IDX_66FACAE4958EF4_61851314` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_dbprocess` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5BD70014064188_75338698` (`client_id`),
  KEY `FK_5BD70014064418_52460373` (`data_client_id`),
  KEY `FK_5BD700140645F1_64494929` (`entity_id`),
  KEY `FK_5BD700140647D3_75665912` (`dataentity_id`),
  KEY `IDX_5BD700140B1EF8_85093010` (`code`) USING BTREE,
  KEY `IDX_5BD700140B2186_57628416` (`description`) USING BTREE,
  CONSTRAINT `FK_5BD70014064188_75338698` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5BD70014064418_52460373` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5BD700140645F1_64494929` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5BD700140647D3_75665912` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_desktopregion` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5A2797D9C84650_90278123` (`client_id`),
  KEY `FK_5A2797D9C848A6_96985181` (`data_client_id`),
  KEY `FK_5A2797D9C84A21_03100985` (`entity_id`),
  KEY `FK_5A2797D9C84B91_29354662` (`dataentity_id`),
  KEY `IDX_5A2797D9E056F2_29702328` (`code`) USING BTREE,
  KEY `IDX_5A2797D9E05D04_37716343` (`description`) USING BTREE,
  CONSTRAINT `FK_5A2797D9C84650_90278123` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5A2797D9C848A6_96985181` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5A2797D9C84A21_03100985` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5A2797D9C84B91_29354662` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_device` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_598877F6B93838_14260667` (`client_id`),
  KEY `FK_598877F6B93AD4_82614839` (`data_client_id`),
  KEY `FK_598877F6B93CE3_20125439` (`entity_id`),
  KEY `FK_598877F6B93ED9_26767748` (`dataentity_id`),
  KEY `IDX_598877F6C10605_69888201` (`code`) USING BTREE,
  KEY `IDX_598877F6C108E2_69594624` (`description`) USING BTREE,
  CONSTRAINT `FK_598877F6B93838_14260667` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598877F6B93AD4_82614839` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598877F6B93CE3_20125439` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_598877F6B93ED9_26767748` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_devicelog` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59943291657687_19061733` (`client_id`),
  KEY `FK_59943291657927_67862173` (`data_client_id`),
  KEY `FK_59943291657B31_93037523` (`entity_id`),
  KEY `FK_59943291657D25_70613690` (`dataentity_id`),
  KEY `IDX_599432916AD846_19234952` (`code`) USING BTREE,
  KEY `IDX_599432916ADAF6_57431420` (`description`) USING BTREE,
  CONSTRAINT `FK_59943291657687_19061733` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59943291657927_67862173` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59943291657B31_93037523` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59943291657D25_70613690` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_displayorder` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59B50466E23911_84172582` (`client_id`),
  KEY `FK_59B50466E23BB0_45154348` (`data_client_id`),
  KEY `FK_59B50466E23DA9_49069585` (`entity_id`),
  KEY `FK_59B50466E23F88_76169869` (`dataentity_id`),
  KEY `IDX_59B50466E6F756_52136244` (`code`) USING BTREE,
  KEY `IDX_59B50466E6F9E4_65602409` (`description`) USING BTREE,
  CONSTRAINT `FK_59B50466E23911_84172582` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59B50466E23BB0_45154348` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59B50466E23DA9_49069585` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59B50466E23F88_76169869` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_document` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5988781655A125_53731870` (`client_id`),
  KEY `FK_5988781655A3B1_70094398` (`data_client_id`),
  KEY `FK_5988781655A5A4_55525109` (`entity_id`),
  KEY `FK_5988781655A790_86885070` (`dataentity_id`),
  KEY `IDX_598878165AF861_39187419` (`code`) USING BTREE,
  KEY `IDX_598878165AFB07_30701199` (`description`) USING BTREE,
  CONSTRAINT `FK_5988781655A125_53731870` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988781655A3B1_70094398` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988781655A5A4_55525109` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5988781655A790_86885070` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_documentrepository` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D6C9C4DAC2B63_78649160` (`client_id`),
  KEY `FK_5D6C9C4DAC2B67_53409260` (`data_client_id`),
  KEY `FK_5D6C9C4DAC2B63_36456433` (`entity_id`),
  KEY `FK_5D6C9C4DAC69E1_74440269` (`dataentity_id`),
  KEY `IDX_5D6C9C4DCAEED0_63780985` (`code`) USING BTREE,
  KEY `IDX_5D6C9C4DCB2D52_26649400` (`description`) USING BTREE,
  CONSTRAINT `FK_5D6C9C4DAC2B63_36456433` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D6C9C4DAC2B63_78649160` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D6C9C4DAC2B67_53409260` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D6C9C4DAC69E1_74440269` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_documentretentiontype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_599D2B7503A8E4_70696532` (`client_id`),
  KEY `FK_599D2B7503AB95_71240388` (`data_client_id`),
  KEY `FK_599D2B7503AD94_31681316` (`entity_id`),
  KEY `FK_599D2B7503AF78_77948404` (`dataentity_id`),
  KEY `IDX_599D2B75086888_94511374` (`code`) USING BTREE,
  KEY `IDX_599D2B75086B64_78722778` (`description`) USING BTREE,
  CONSTRAINT `FK_599D2B7503A8E4_70696532` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599D2B7503AB95_71240388` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599D2B7503AD94_31681316` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_599D2B7503AF78_77948404` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_documenttype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_598B39D7953475_57300338` (`client_id`),
  KEY `FK_598B39D7953708_71369500` (`data_client_id`),
  KEY `FK_598B39D79538F3_94754877` (`entity_id`),
  KEY `FK_598B39D7953AD4_74775629` (`dataentity_id`),
  KEY `IDX_598B39D79A9480_76417726` (`code`) USING BTREE,
  KEY `IDX_598B39D79A9734_39655065` (`description`) USING BTREE,
  CONSTRAINT `FK_598B39D7953475_57300338` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598B39D7953708_71369500` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598B39D79538F3_94754877` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_598B39D7953AD4_74775629` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_employersize` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60507B3BBDD925_64027085` (`code`) USING BTREE,
  KEY `IDX_60507B3BBDDB82_17865240` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_entity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_599ED05AE867F8_38197918` (`client_id`),
  KEY `FK_599ED05AE86B12_46280425` (`data_client_id`),
  KEY `FK_599ED05AE86D77_44536179` (`entity_id`),
  KEY `FK_599ED05AE86FD4_59948732` (`dataentity_id`),
  KEY `IDX_599ED05AEDF8F6_91222959` (`code`) USING BTREE,
  KEY `IDX_599ED05AEDFC16_27457583` (`description`) USING BTREE,
  CONSTRAINT `FK_599ED05AE867F8_38197918` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599ED05AE86B12_46280425` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599ED05AE86D77_44536179` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_599ED05AE86FD4_59948732` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_entity_relationship` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_605C755C22B774_81372011` (`code`) USING BTREE,
  KEY `IDX_605C755C22B948_44945671` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_entityoperation` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_599ED0F67CDA67_79378743` (`client_id`),
  KEY `FK_599ED0F67CDD62_11698388` (`data_client_id`),
  KEY `FK_599ED0F67CDFC9_12995094` (`entity_id`),
  KEY `FK_599ED0F67CE222_96567623` (`dataentity_id`),
  KEY `IDX_599ED0F681E215_91685896` (`code`) USING BTREE,
  KEY `IDX_599ED0F681E510_57656133` (`description`) USING BTREE,
  CONSTRAINT `FK_599ED0F67CDA67_79378743` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599ED0F67CDD62_11698388` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599ED0F67CDFC9_12995094` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_599ED0F67CE222_96567623` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_entityoperation_default` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_599ED103C43F52_61274675` (`client_id`),
  KEY `FK_599ED103C44278_50023845` (`data_client_id`),
  KEY `FK_599ED103C444D3_80050345` (`entity_id`),
  KEY `FK_599ED103C44723_81342150` (`dataentity_id`),
  KEY `IDX_599ED103CA1F50_47707437` (`code`) USING BTREE,
  KEY `IDX_599ED103CA2436_63724194` (`description`) USING BTREE,
  CONSTRAINT `FK_599ED103C43F52_61274675` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599ED103C44278_50023845` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_599ED103C444D3_80050345` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_599ED103C44723_81342150` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_faq` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60507CD14155A5_69731326` (`code`) USING BTREE,
  KEY `IDX_60507CD1415767_78804871` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_filedatatype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA116086A2994_27752361` (`code`) USING BTREE,
  KEY `IDX_5EA116086A2C24_74787701` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_filefieldexclusion` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF2D667711BD8_80048802` (`code`) USING BTREE,
  KEY `IDX_5EF2D667711E45_26633584` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_filefieldmapping` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA253E4A087A2_68494631` (`code`) USING BTREE,
  KEY `IDX_5EA253E4A08A31_89274763` (`description`) USING BTREE,
  KEY `IDX_5EF2D6B8E8D969_23217977` (`code`) USING BTREE,
  KEY `IDX_5EF2D6B8E8DBB4_58536736` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_fileformat` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA1296DADF1F8_90652678` (`code`) USING BTREE,
  KEY `IDX_5EA1296DADF515_65644759` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_fileformat_field` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA13179F1E624_74204387` (`code`) USING BTREE,
  KEY `IDX_5EA13179F1EC62_42676139` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_fileformattemplate` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA11788AA6A01_40917657` (`code`) USING BTREE,
  KEY `IDX_5EA11788AA6CB7_88606362` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_fileformattemplate_field` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA1293B362C69_01169403` (`code`) USING BTREE,
  KEY `IDX_5EA1293B362F37_13439619` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_fileformattype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA1176EECA207_74823571` (`code`) USING BTREE,
  KEY `IDX_5EA1176EECA484_15682823` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_filesystem` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_61F414BB7BC579_01203263` (`code`) USING BTREE,
  KEY `IDX_61F414BB7BC8D6_95151571` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_filetype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EA11311CA1172_88008324` (`code`) USING BTREE,
  KEY `IDX_5EA11311CA1420_16714896` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formdatatype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4A906BD77F19_22263504` (`client_id`),
  KEY `FK_5D4A906BD77F12_36759248` (`data_client_id`),
  KEY `FK_5D4A906BD7BD98_56615226` (`entity_id`),
  KEY `FK_5D4A906BD7BD92_07376840` (`dataentity_id`),
  KEY `IDX_5D4A906C039593_46346960` (`code`) USING BTREE,
  KEY `IDX_5D4A906C039594_41441807` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4A906BD77F12_36759248` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4A906BD77F19_22263504` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4A906BD7BD92_07376840` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4A906BD7BD98_56615226` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formdatatype_property` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4A90BC07A1A9_81449013` (`client_id`),
  KEY `FK_5D4A90BC07E026_57758294` (`data_client_id`),
  KEY `FK_5D4A90BC07E025_61322845` (`entity_id`),
  KEY `FK_5D4A90BC07E027_66555404` (`dataentity_id`),
  KEY `IDX_5D4A90BC272097_91574818` (`code`) USING BTREE,
  KEY `IDX_5D4A90BC272098_70364364` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4A90BC07A1A9_81449013` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4A90BC07E025_61322845` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4A90BC07E026_57758294` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4A90BC07E027_66555404` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formlayout` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4D59AE138303_89586139` (`client_id`),
  KEY `FK_5D4D59AE138302_79846893` (`data_client_id`),
  KEY `FK_5D4D59AE138309_31361165` (`entity_id`),
  KEY `FK_5D4D59AE13C184_85991244` (`dataentity_id`),
  KEY `IDX_5D4D59AE3D04A1_45739277` (`code`) USING BTREE,
  KEY `IDX_5D4D59AE3D04A2_11579797` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4D59AE138302_79846893` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D59AE138303_89586139` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D59AE138309_31361165` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4D59AE13C184_85991244` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formsection_field` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4D59FDD5FC46_54479340` (`client_id`),
  KEY `FK_5D4D59FDD5FC46_95242082` (`data_client_id`),
  KEY `FK_5D4D59FDD5FC44_63636758` (`entity_id`),
  KEY `FK_5D4D59FDD63AC8_92560902` (`dataentity_id`),
  KEY `IDX_5D4D59FE025148_34262839` (`code`) USING BTREE,
  KEY `IDX_5D4D59FE025147_47672973` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4D59FDD5FC44_63636758` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4D59FDD5FC46_54479340` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D59FDD5FC46_95242082` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D59FDD63AC8_92560902` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formsection_field_property` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4D5AE5F25F92_72662820` (`client_id`),
  KEY `FK_5D4D5AE5F29E17_05854309` (`data_client_id`),
  KEY `FK_5D4D5AE5F29E12_83087574` (`entity_id`),
  KEY `FK_5D4D5AE5F29E14_78581090` (`dataentity_id`),
  KEY `IDX_5D4D5AE6225E14_18415564` (`code`) USING BTREE,
  KEY `IDX_5D4D5AE6229C95_08460072` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4D5AE5F25F92_72662820` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D5AE5F29E12_83087574` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4D5AE5F29E14_78581090` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4D5AE5F29E17_05854309` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formsectiontemplate` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4BD08A7985C7_57872482` (`client_id`),
  KEY `FK_5D4BD08A7985C3_00425153` (`data_client_id`),
  KEY `FK_5D4BD08A79C449_93122466` (`entity_id`),
  KEY `FK_5D4BD08A79C446_62939057` (`dataentity_id`),
  KEY `IDX_5D4BD08A994333_30139588` (`code`) USING BTREE,
  KEY `IDX_5D4BD08A9981B9_36065057` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4BD08A7985C3_00425153` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4BD08A7985C7_57872482` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4BD08A79C446_62939057` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4BD08A79C449_93122466` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formsectiontemplate_field` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4BD0A43B61C2_75566134` (`client_id`),
  KEY `FK_5D4BD0A43B61C3_68336146` (`data_client_id`),
  KEY `FK_5D4BD0A43B61C4_69479832` (`entity_id`),
  KEY `FK_5D4BD0A43BA041_04970646` (`dataentity_id`),
  KEY `IDX_5D4BD0A45B1F41_06654779` (`code`) USING BTREE,
  KEY `IDX_5D4BD0A45B1F45_32035504` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4BD0A43B61C2_75566134` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4BD0A43B61C3_68336146` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4BD0A43B61C4_69479832` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4BD0A43BA041_04970646` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formsectiontemplate_field_property` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4BD0C2AB22A6_85641570` (`client_id`),
  KEY `FK_5D4BD0C2AB22A4_72818774` (`data_client_id`),
  KEY `FK_5D4BD0C2AB22A3_67831632` (`entity_id`),
  KEY `FK_5D4BD0C2AB22A7_63228248` (`dataentity_id`),
  KEY `IDX_5D4BD0C2CD5121_32701269` (`code`) USING BTREE,
  KEY `IDX_5D4BD0C2CD5127_32918606` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4BD0C2AB22A3_67831632` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4BD0C2AB22A4_72818774` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4BD0C2AB22A6_85641570` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4BD0C2AB22A7_63228248` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_formtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62509827765209_37234573` (`code`) USING BTREE,
  KEY `IDX_625098277655E5_17676969` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_fragment` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60507E06003389_71056529` (`code`) USING BTREE,
  KEY `IDX_60507E06003551_77076508` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_gender` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_h_gender_client_id` (`client_id`),
  KEY `FK_h_gender_data_client_id` (`data_client_id`),
  KEY `FK_h_gender_entity_id` (`entity_id`),
  KEY `FK_h_gender_dataentity_id` (`dataentity_id`),
  KEY `IDX_h_gender_code` (`code`) USING BTREE,
  KEY `IDX_h_gender_description` (`description`) USING BTREE,
  CONSTRAINT `FK_h_gender_client_id` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_gender_data_client_id` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_gender_dataentity_id` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_h_gender_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_honorific` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_h_honorific_client_id` (`client_id`),
  KEY `FK_h_honorific_data_client_id` (`data_client_id`),
  KEY `FK_h_honorific_entity_id` (`entity_id`),
  KEY `FK_h_honorific_dataentity_id` (`dataentity_id`),
  KEY `IDX_h_honorific_code` (`code`) USING BTREE,
  KEY `IDX_h_honorific_description` (`description`) USING BTREE,
  CONSTRAINT `FK_h_honorific_client_id` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_honorific_data_client_id` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_honorific_dataentity_id` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_h_honorific_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_icon` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_643D0BBB4B7FD1_13038376` (`code`) USING BTREE,
  KEY `IDX_643D0BBB4B8085_03382253` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_import` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_680F14D9EA3D13_52748769` (`code`) USING BTREE,
  KEY `IDX_680F14D9EA3E00_40860197` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_import_zonebuy` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62B46F2BDBDC01_90900835` (`code`) USING BTREE,
  KEY `IDX_62B46F2BDBDDF1_50454344` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_import_zonebuy_set` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62B46F4391AD57_37049611` (`code`) USING BTREE,
  KEY `IDX_62B46F4391B0A2_37795723` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_inbound_whitelist` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_600678D7A4A319_49297786` (`code`) USING BTREE,
  KEY `IDX_600678D7A4A510_08130506` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_inbounddata` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5FC7507C4BACA1_10941414` (`code`) USING BTREE,
  KEY `IDX_5FC7507C4BAF40_57866155` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_industrytype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60507EF888F577_25206492` (`code`) USING BTREE,
  KEY `IDX_60507EF888F732_14516720` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationinbound` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D70BE762CF499_44312625` (`client_id`),
  KEY `FK_5D70BE762CF490_96456714` (`data_client_id`),
  KEY `FK_5D70BE762D3316_37105846` (`entity_id`),
  KEY `FK_5D70BE762D3315_50013499` (`dataentity_id`),
  KEY `IDX_5D70BE76519418_82087288` (`code`) USING BTREE,
  KEY `IDX_5D70BE76519412_54052676` (`description`) USING BTREE,
  CONSTRAINT `FK_5D70BE762CF490_96456714` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D70BE762CF499_44312625` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D70BE762D3315_50013499` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70BE762D3316_37105846` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationinboundplugin` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D761C84590319_92058373` (`client_id`),
  KEY `FK_5D761C84590313_89864704` (`data_client_id`),
  KEY `FK_5D761C84594198_67140051` (`entity_id`),
  KEY `FK_5D761C84594196_20213109` (`dataentity_id`),
  KEY `IDX_5D761C84768E03_58088376` (`code`) USING BTREE,
  KEY `IDX_5D761C8476CC81_40495440` (`description`) USING BTREE,
  CONSTRAINT `FK_5D761C84590313_89864704` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D761C84590319_92058373` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D761C84594196_20213109` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D761C84594198_67140051` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationinboundtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D70B4B3D4F312_64880426` (`client_id`),
  KEY `FK_5D70B4B3D4F315_01127210` (`data_client_id`),
  KEY `FK_5D70B4B3D4F314_06575900` (`entity_id`),
  KEY `FK_5D70B4B3D4F310_92515519` (`dataentity_id`),
  KEY `IDX_5D70B4B4028097_19516066` (`code`) USING BTREE,
  KEY `IDX_5D70B4B402BF17_16471419` (`description`) USING BTREE,
  CONSTRAINT `FK_5D70B4B3D4F310_92515519` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70B4B3D4F312_64880426` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D70B4B3D4F314_06575900` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70B4B3D4F315_01127210` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationoutbound` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D70BE959100D9_50981131` (`client_id`),
  KEY `FK_5D70BE959100D1_47306693` (`data_client_id`),
  KEY `FK_5D70BE959100D5_00062395` (`entity_id`),
  KEY `FK_5D70BE959100D1_17455399` (`dataentity_id`),
  KEY `IDX_5D70BE95B0BE58_36262409` (`code`) USING BTREE,
  KEY `IDX_5D70BE95B0BE51_55489729` (`description`) USING BTREE,
  CONSTRAINT `FK_5D70BE959100D1_17455399` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70BE959100D1_47306693` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D70BE959100D5_00062395` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70BE959100D9_50981131` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationoutboundplugin` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D761CC8568B29_76467640` (`client_id`),
  KEY `FK_5D761CC8568B27_18318169` (`data_client_id`),
  KEY `FK_5D761CC856C9A8_10171628` (`entity_id`),
  KEY `FK_5D761CC856C9A9_97946534` (`dataentity_id`),
  KEY `IDX_5D761CC8778126_99622933` (`code`) USING BTREE,
  KEY `IDX_5D761CC8778125_48831997` (`description`) USING BTREE,
  CONSTRAINT `FK_5D761CC8568B27_18318169` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D761CC8568B29_76467640` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D761CC856C9A8_10171628` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D761CC856C9A9_97946534` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationoutboundtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D70B4E66941D7_28286685` (`client_id`),
  KEY `FK_5D70B4E66941D1_71950924` (`data_client_id`),
  KEY `FK_5D70B4E66941D2_41244247` (`entity_id`),
  KEY `FK_5D70B4E66941D0_01218696` (`dataentity_id`),
  KEY `IDX_5D70B4E6893DD6_49442677` (`code`) USING BTREE,
  KEY `IDX_5D70B4E6893DD5_73859392` (`description`) USING BTREE,
  CONSTRAINT `FK_5D70B4E66941D0_01218696` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70B4E66941D1_71950924` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D70B4E66941D2_41244247` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70B4E66941D7_28286685` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationtaskinbound` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_606581B4605CD3_72984590` (`code`) USING BTREE,
  KEY `IDX_606581B4605E84_36585870` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_integrationtaskoutbound` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_606581D09C6097_84072795` (`code`) USING BTREE,
  KEY `IDX_606581D09C6282_85810254` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_internalmessage` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF5C63E6DE482_58049750` (`code`) USING BTREE,
  KEY `IDX_5EF5C63E6DE743_36507169` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_internalmessagefolder` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF5C657F40676_79913127` (`code`) USING BTREE,
  KEY `IDX_5EF5C657F40911_34955888` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_layout_formsection` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D4D59E3305467_61828903` (`client_id`),
  KEY `FK_5D4D59E3305463_44987683` (`data_client_id`),
  KEY `FK_5D4D59E33092E5_78506933` (`entity_id`),
  KEY `FK_5D4D59E33092E7_68815355` (`dataentity_id`),
  KEY `IDX_5D4D59E34F5669_25911537` (`code`) USING BTREE,
  KEY `IDX_5D4D59E34F5669_95439993` (`description`) USING BTREE,
  CONSTRAINT `FK_5D4D59E3305463_44987683` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D59E3305467_61828903` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D4D59E33092E5_78506933` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D4D59E33092E7_68815355` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_layout_reportsection` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347BE38BED40_08893965` (`code`) USING BTREE,
  KEY `IDX_62347BE38BF0F7_38481660` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_log_printjob` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E9D3A73549CD4_01898947` (`code`) USING BTREE,
  KEY `IDX_5E9D3A7354A079_86223800` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_mapprovider` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67FF8D21C740B7_98535261` (`code`) USING BTREE,
  KEY `IDX_67FF8D21C74182_94804213` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_messageprepared` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5C08BB49DE5D69_64571836` (`client_id`),
  KEY `FK_5C08BB49DE6003_29451302` (`data_client_id`),
  KEY `FK_5C08BB49DE6211_52302769` (`entity_id`),
  KEY `FK_5C08BB49DE6404_78128407` (`dataentity_id`),
  KEY `IDX_5C08BB49E3E519_09859619` (`code`) USING BTREE,
  KEY `IDX_5C08BB49E3E7A0_12583359` (`description`) USING BTREE,
  CONSTRAINT `FK_5C08BB49DE5D69_64571836` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5C08BB49DE6003_29451302` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5C08BB49DE6211_52302769` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5C08BB49DE6404_78128407` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_messagetemplate` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59887851090810_14668720` (`client_id`),
  KEY `FK_59887851090AB8_63289069` (`data_client_id`),
  KEY `FK_59887851090CA5_58948018` (`entity_id`),
  KEY `FK_59887851090E97_54619673` (`dataentity_id`),
  KEY `IDX_598878510FF704_46503136` (`code`) USING BTREE,
  KEY `IDX_598878510FFA74_28824359` (`description`) USING BTREE,
  CONSTRAINT `FK_59887851090810_14668720` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59887851090AB8_63289069` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59887851090CA5_58948018` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59887851090E97_54619673` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_messagetemplatetype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_598B3E2E6F3A36_72738520` (`client_id`),
  KEY `FK_598B3E2E6F3CC1_68208539` (`data_client_id`),
  KEY `FK_598B3E2E6F3EB3_28796127` (`entity_id`),
  KEY `FK_598B3E2E6F4094_52931880` (`dataentity_id`),
  KEY `IDX_598B3E2E74E3E2_14808995` (`code`) USING BTREE,
  KEY `IDX_598B3E2E74E704_20820623` (`description`) USING BTREE,
  CONSTRAINT `FK_598B3E2E6F3A36_72738520` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598B3E2E6F3CC1_68208539` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598B3E2E6F3EB3_28796127` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_598B3E2E6F4094_52931880` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_mobileform` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F15399D563518_59520855` (`code`) USING BTREE,
  KEY `IDX_5F15399D5637A6_12522982` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_module` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229C95379D29_44201986` (`client_id`),
  KEY `FK_59229C95379FB3_20254752` (`data_client_id`),
  KEY `FK_59229C9537A1F1_56297642` (`entity_id`),
  KEY `FK_59229C9537A421_49596897` (`dataentity_id`),
  KEY `IDX_59229C953CB3C1_13900128` (`code`) USING BTREE,
  KEY `IDX_59229C953CB6D7_59969638` (`description`) USING BTREE,
  CONSTRAINT `FK_59229C95379D29_44201986` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C95379FB3_20254752` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C9537A1F1_56297642` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229C9537A421_49596897` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_module_module` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D95B8133B8326_46012843` (`client_id`),
  KEY `FK_5D95B8133B8575_13239114` (`data_client_id`),
  KEY `FK_5D95B8133B8709_01710827` (`entity_id`),
  KEY `FK_5D95B8133B8896_69435780` (`dataentity_id`),
  KEY `IDX_5D95B813449C21_24993017` (`code`) USING BTREE,
  KEY `IDX_5D95B813449EB0_51550559` (`description`) USING BTREE,
  CONSTRAINT `FK_5D95B8133B8326_46012843` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D95B8133B8575_13239114` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D95B8133B8709_01710827` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D95B8133B8896_69435780` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_myclient` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_650B396F0EEEC3_02242709` (`code`) USING BTREE,
  KEY `IDX_650B396F0EFA85_82976978` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_navitem` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F5F37FCD778B6_33422819` (`code`) USING BTREE,
  KEY `IDX_5F5F37FCD77CE0_10050483` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_negotiateddiscount` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_643D16C45AF5F8_98019756` (`code`) USING BTREE,
  KEY `IDX_643D16C45AF6E5_50720341` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_negotiatedrate` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_643D16D3119DF6_87965971` (`code`) USING BTREE,
  KEY `IDX_643D16D3119EC3_23725366` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_outbounddata` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5FC7506E95E971_31664323` (`code`) USING BTREE,
  KEY `IDX_5FC7506E95EBF7_30351233` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_payment` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02D28AA6D2B6_89202043` (`code`) USING BTREE,
  KEY `IDX_5F02D28AA6D545_56145212` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_paymentmethod` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02D277B699A9_40436586` (`code`) USING BTREE,
  KEY `IDX_5F02D277B69C01_55816128` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_paymentstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02C8F7640A30_25964559` (`code`) USING BTREE,
  KEY `IDX_5F02C8F7640CB3_06298531` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_permission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229C8D6B2EC2_20843385` (`client_id`),
  KEY `FK_59229C8D6B3336_94556855` (`data_client_id`),
  KEY `FK_59229C8D6B3777_14891138` (`entity_id`),
  KEY `FK_59229C8D6B3BA6_88347398` (`dataentity_id`),
  KEY `IDX_59229C8D700437_63675380` (`code`) USING BTREE,
  KEY `IDX_59229C8D700735_01859276` (`description`) USING BTREE,
  CONSTRAINT `FK_59229C8D6B2EC2_20843385` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C8D6B3336_94556855` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C8D6B3777_14891138` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229C8D6B3BA6_88347398` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_permissioncategory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229CA0B92C02_19162573` (`client_id`),
  KEY `FK_59229CA0B92E89_47791775` (`data_client_id`),
  KEY `FK_59229CA0B930C0_52246813` (`entity_id`),
  KEY `FK_59229CA0B93315_28627365` (`dataentity_id`),
  KEY `IDX_59229CA0BDF5A4_63017705` (`code`) USING BTREE,
  KEY `IDX_59229CA0BDF8A9_31372683` (`description`) USING BTREE,
  CONSTRAINT `FK_59229CA0B92C02_19162573` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229CA0B92E89_47791775` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229CA0B930C0_52246813` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229CA0B93315_28627365` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_printer` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_66FABACBCDF7D2_88248488` (`code`) USING BTREE,
  KEY `IDX_66FABACBCDF8A5_40394446` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_printerpurpose` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_543A4AEC_44D4_4C5B_A629_E391DD08E3C1` (`code`) USING BTREE,
  KEY `IDX_DC51B574_8896_4108_A70B_D32BC1AA4FA1` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_printertype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF5C6749E8AD1_98359769` (`code`) USING BTREE,
  KEY `IDX_5EF5C6749E8D72_04329209` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_printjob` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E9D3A377885C9_05993047` (`code`) USING BTREE,
  KEY `IDX_5E9D3A37788841_36977165` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_printqueue` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E9D3A1C385093_04192043` (`code`) USING BTREE,
  KEY `IDX_5E9D3A1C385376_74473187` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_product` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BCEB178D80_98172055` (`code`) USING BTREE,
  KEY `IDX_5F02BCEB178FE1_69835725` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_producttype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BD215A2104_23465369` (`code`) USING BTREE,
  KEY `IDX_5F02BD215A2364_28035949` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_profile` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229C72DB0B37_45058074` (`client_id`),
  KEY `FK_59229C72DB0DC5_38978988` (`data_client_id`),
  KEY `FK_59229C72DB1004_38499191` (`entity_id`),
  KEY `FK_59229C72DB1243_56626342` (`dataentity_id`),
  KEY `IDX_59229C72E56679_96709202` (`code`) USING BTREE,
  KEY `IDX_59229C72E56999_44853099` (`description`) USING BTREE,
  CONSTRAINT `FK_59229C72DB0B37_45058074` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C72DB0DC5_38978988` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C72DB1004_38499191` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229C72DB1243_56626342` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_profile_permission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229FB6B362E4_48296715` (`client_id`),
  KEY `FK_59229FB6B36542_04814651` (`data_client_id`),
  KEY `FK_59229FB6B36748_09465714` (`entity_id`),
  KEY `FK_59229FB6B36947_70202894` (`dataentity_id`),
  KEY `IDX_59229FB6B87943_43866110` (`code`) USING BTREE,
  KEY `IDX_59229FB6B87C46_77934284` (`description`) USING BTREE,
  CONSTRAINT `FK_59229FB6B362E4_48296715` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229FB6B36542_04814651` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229FB6B36748_09465714` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229FB6B36947_70202894` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_project` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_604F8356BC0AC0_97284923` (`code`) USING BTREE,
  KEY `IDX_604F8356BC0CB5_80958427` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectbillingcategory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114FDCB12AE2_02111591` (`code`) USING BTREE,
  KEY `IDX_60114FDCB12D92_28064161` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectcampaign` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601144AF561E21_48847435` (`code`) USING BTREE,
  KEY `IDX_601144AF5620D0_63464777` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectcampaignstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011451FE85602_52506680` (`code`) USING BTREE,
  KEY `IDX_6011451FE85829_21284259` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectcampaigntask` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114F962B86D7_11131176` (`code`) USING BTREE,
  KEY `IDX_60114F962B8978_70557272` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectcampaigntype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601144F3453C76_39200369` (`code`) USING BTREE,
  KEY `IDX_601144F3453F25_08807132` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectcostcategory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114F5783A287_11623384` (`code`) USING BTREE,
  KEY `IDX_60114F5783A522_77573377` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectissue` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601147BAECF553_24611463` (`code`) USING BTREE,
  KEY `IDX_601147BAECF962_24078135` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectissuepriority` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601144438AEA86_45884500` (`code`) USING BTREE,
  KEY `IDX_601144438AEC94_77893722` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectissuestatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_604F825B1ECB83_02031723` (`code`) USING BTREE,
  KEY `IDX_604F825B1ECD59_86740901` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectissuetype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011440A1AEAF5_24168311` (`code`) USING BTREE,
  KEY `IDX_6011440A1AEDC4_44799053` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectparticipant` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114F15691BC4_46895494` (`code`) USING BTREE,
  KEY `IDX_60114F15691E82_63404912` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectparticipantactivity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601146599B62F3_84384294` (`code`) USING BTREE,
  KEY `IDX_601146599B6510_15302437` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectparticipanttype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601143AA04C3B4_56600017` (`code`) USING BTREE,
  KEY `IDX_601143AA04C6F7_43771357` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectpriority` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114375B72510_90750690` (`code`) USING BTREE,
  KEY `IDX_60114375B72847_11345226` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectresource` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114DBFE63F25_06505287` (`code`) USING BTREE,
  KEY `IDX_60114DBFE64122_68005221` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectresourceactivity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011486B4A68F0_26091642` (`code`) USING BTREE,
  KEY `IDX_6011486B4A6D62_09436863` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectresourcetype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011434175DAB2_40807940` (`code`) USING BTREE,
  KEY `IDX_6011434175DEC9_42970893` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectstakeholder` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114EC779ECF6_81784971` (`code`) USING BTREE,
  KEY `IDX_60114EC779EFC4_05641188` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projectstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_601142FD635E34_66364330` (`code`) USING BTREE,
  KEY `IDX_601142FD636354_98362641` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projecttask` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011475C8B6B14_37278412` (`code`) USING BTREE,
  KEY `IDX_6011475C8B6F66_22956279` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projecttaskpriority` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011428DC7DCB3_55563763` (`code`) USING BTREE,
  KEY `IDX_6011428DC7DEE0_84963745` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projecttaskstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6011412EB15D26_23849435` (`code`) USING BTREE,
  KEY `IDX_6011412EB15FB5_73030298` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_projecttype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60113CD7BCE904_76912970` (`code`) USING BTREE,
  KEY `IDX_60113CD7BCEB95_60191926` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_receipt` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F462F384F9C59_33645627` (`code`) USING BTREE,
  KEY `IDX_5F462F384FA154_49568208` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_registration` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59887779831461_03516076` (`client_id`),
  KEY `FK_59887779831702_42859403` (`data_client_id`),
  KEY `FK_598877798318F4_96353398` (`entity_id`),
  KEY `FK_59887779831AE0_57600818` (`dataentity_id`),
  KEY `IDX_59887779881E92_10509724` (`code`) USING BTREE,
  KEY `IDX_598877798821C9_94673592` (`description`) USING BTREE,
  CONSTRAINT `FK_59887779831461_03516076` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59887779831702_42859403` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_598877798318F4_96353398` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59887779831AE0_57600818` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_registrationtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5988776C3A1A59_17584171` (`client_id`),
  KEY `FK_5988776C3A1CF5_18079491` (`data_client_id`),
  KEY `FK_5988776C3A1EE1_86035336` (`entity_id`),
  KEY `FK_5988776C3A20D1_41695010` (`dataentity_id`),
  KEY `IDX_5988776C415170_66107262` (`code`) USING BTREE,
  KEY `IDX_5988776C415414_95520646` (`description`) USING BTREE,
  CONSTRAINT `FK_5988776C3A1A59_17584171` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988776C3A1CF5_18079491` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988776C3A1EE1_86035336` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5988776C3A20D1_41695010` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reminder` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_58A14714F02A03_28597639` (`client_id`),
  KEY `FK_58A14714F02FF9_63319772` (`data_client_id`),
  KEY `FK_58A14714F03487_10655283` (`entity_id`),
  KEY `FK_58A14714F03922_93862007` (`dataentity_id`),
  KEY `IDX_58A14715149DD9_80778089` (`code`) USING BTREE,
  KEY `IDX_58A1471514A431_91671949` (`description`) USING BTREE,
  CONSTRAINT `FK_58A14714F02A03_28597639` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_58A14714F02FF9_63319772` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_58A14714F03487_10655283` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_58A14714F03922_93862007` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportdatatype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347D2C6C3459_50161170` (`code`) USING BTREE,
  KEY `IDX_62347D2C6C37B9_07364857` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportdatatype_property` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347D84A636B4_67443672` (`code`) USING BTREE,
  KEY `IDX_62347D84A63881_03336569` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportlayout` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347C4619C8A6_48520268` (`code`) USING BTREE,
  KEY `IDX_62347C4619CC13_68658053` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportsection_field` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347B7A583CB0_88313554` (`code`) USING BTREE,
  KEY `IDX_62347B7A583FC1_54877482` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportsection_field_property` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347E2757F0F0_53002600` (`code`) USING BTREE,
  KEY `IDX_62347E2757FC49_88310506` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportsectiontemplate` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347A3E187DF9_17816711` (`code`) USING BTREE,
  KEY `IDX_62347A3E188025_12438064` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportsectiontemplate_field` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347AAD7CF035_92672866` (`code`) USING BTREE,
  KEY `IDX_62347AAD7CF341_37442729` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_reportsectiontemplate_field_property` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347AFC6210D5_91656589` (`code`) USING BTREE,
  KEY `IDX_62347AFC621528_95542414` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_resetpassword` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5A21AD138344C2_60260852` (`client_id`),
  KEY `FK_5A21AD138344C0_37899647` (`data_client_id`),
  KEY `FK_5A21AD138344C7_48487682` (`entity_id`),
  KEY `FK_5A21AD138344C8_72893361` (`dataentity_id`),
  KEY `IDX_5A21AD1386A050_70823898` (`code`) USING BTREE,
  KEY `IDX_5A21AD1386A051_03845344` (`description`) USING BTREE,
  CONSTRAINT `FK_5A21AD138344C0_37899647` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5A21AD138344C2_60260852` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5A21AD138344C7_48487682` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5A21AD138344C8_72893361` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_rndgrant` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60115015DD13C3_65372526` (`code`) USING BTREE,
  KEY `IDX_60115015DD1843_19369709` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_roster` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60114CB4BD12E3_36287261` (`code`) USING BTREE,
  KEY `IDX_60114CB4BD1B71_08847093` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_rostertemplate` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_602237E791AA19_73842861` (`code`) USING BTREE,
  KEY `IDX_602237E791ABC8_43668328` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_rostertemplate_detail` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60223828C34D25_17873773` (`code`) USING BTREE,
  KEY `IDX_60223828C34EE9_84126728` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_rostertemplate_projectparticipant` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60223847AFC210_57763590` (`code`) USING BTREE,
  KEY `IDX_60223847AFC3D5_34270454` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_salespersoncode` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_650B6B29CE10E8_99337459` (`code`) USING BTREE,
  KEY `IDX_650B6B29CE1836_87759312` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_salespersoncodelog` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_650B6B4AAAAA72_90896500` (`code`) USING BTREE,
  KEY `IDX_650B6B4AAAADB6_92777449` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_schemachange` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5BC54FD060C315_56261861` (`client_id`),
  KEY `FK_5BC54FD060C5A6_89551782` (`data_client_id`),
  KEY `FK_5BC54FD060C799_42269733` (`entity_id`),
  KEY `FK_5BC54FD060C975_39944266` (`dataentity_id`),
  KEY `IDX_5BC54FD06590C0_50499841` (`code`) USING BTREE,
  KEY `IDX_5BC54FD0659345_19732613` (`description`) USING BTREE,
  CONSTRAINT `FK_5BC54FD060C315_56261861` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5BC54FD060C5A6_89551782` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5BC54FD060C799_42269733` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5BC54FD060C975_39944266` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_securitychart` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_68664D60AF3231_20903018` (`code`) USING BTREE,
  KEY `IDX_68664D60AF3306_11706376` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_securitydashboard_chart` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_68664F532A8470_52609699` (`code`) USING BTREE,
  KEY `IDX_68664F532A8540_37311310` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_sequence` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6528B63C7E77A2_78606052` (`code`) USING BTREE,
  KEY `IDX_6528B63C7E80C5_89548392` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_sequencetype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6528B8B39A2128_84805387` (`code`) USING BTREE,
  KEY `IDX_6528B8B39A2605_89096114` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_server` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D70A50AD94558_36274170` (`client_id`),
  KEY `FK_5D70A50AD94552_40657239` (`data_client_id`),
  KEY `FK_5D70A50AD94555_78003416` (`entity_id`),
  KEY `FK_5D70A50AD94552_85416562` (`dataentity_id`),
  KEY `IDX_5D70A50B0461C8_32539067` (`code`) USING BTREE,
  KEY `IDX_5D70A50B0461C0_49761709` (`description`) USING BTREE,
  CONSTRAINT `FK_5D70A50AD94552_40657239` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D70A50AD94552_85416562` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70A50AD94555_78003416` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D70A50AD94558_36274170` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_serverprotocol` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D709F05467FE3_09290877` (`client_id`),
  KEY `FK_5D709F05467FE0_31830478` (`data_client_id`),
  KEY `FK_5D709F05467FE3_66723028` (`entity_id`),
  KEY `FK_5D709F05467FE1_75408590` (`dataentity_id`),
  KEY `IDX_5D709F05667BE5_56793749` (`code`) USING BTREE,
  KEY `IDX_5D709F05667BE8_17502338` (`description`) USING BTREE,
  CONSTRAINT `FK_5D709F05467FE0_31830478` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D709F05467FE1_75408590` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D709F05467FE3_09290877` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D709F05467FE3_66723028` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_settingmodule` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_605AF4B93F8895_57862085` (`code`) USING BTREE,
  KEY `IDX_605AF4B93F8A47_66372782` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_smhypothesis` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60646CC3A58BF9_22824014` (`code`) USING BTREE,
  KEY `IDX_60646CC3A58E51_91014898` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_smobservation` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6064609E7C83B3_66404454` (`code`) USING BTREE,
  KEY `IDX_6064609E7C8580_40791299` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_smquestion` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60642C955E0959_45812146` (`code`) USING BTREE,
  KEY `IDX_60642C955E0B17_48093616` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_smresearch` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_64799DBD09DEC1_56061993` (`code`) USING BTREE,
  KEY `IDX_64799DBD09DF90_36866867` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_smtest` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60646156A14E90_64864405` (`code`) USING BTREE,
  KEY `IDX_60646156A15062_70454994` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_softwarelicence` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF56896BEB638_41734538` (`code`) USING BTREE,
  KEY `IDX_5EF56896BEBA04_41081845` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_softwarelicencetype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF5693E5F9114_64138941` (`code`) USING BTREE,
  KEY `IDX_5EF5693E5F9538_16188217` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_sprint` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60118C1A98E8B2_83000167` (`code`) USING BTREE,
  KEY `IDX_60118C1A98EC10_22240717` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_startupitem` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5988782B8E7F61_09702609` (`client_id`),
  KEY `FK_5988782B8E8281_06178367` (`data_client_id`),
  KEY `FK_5988782B8E8467_32113897` (`entity_id`),
  KEY `FK_5988782B8E8635_08771423` (`dataentity_id`),
  KEY `IDX_5988782B9364F7_87591333` (`code`) USING BTREE,
  KEY `IDX_5988782B9367A3_25224107` (`description`) USING BTREE,
  CONSTRAINT `FK_5988782B8E7F61_09702609` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988782B8E8281_06178367` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5988782B8E8467_32113897` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5988782B8E8635_08771423` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_state` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_h_state_client_id` (`client_id`),
  KEY `FK_h_state_data_client_id` (`data_client_id`),
  KEY `FK_h_state_entity_id` (`entity_id`),
  KEY `FK_h_state_dataentity_id` (`dataentity_id`),
  KEY `IDX_h_state_code` (`code`) USING BTREE,
  KEY `IDX_h_state_description` (`description`) USING BTREE,
  CONSTRAINT `FK_h_state_client_id` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_state_data_client_id` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_state_dataentity_id` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_h_state_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_suburb` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_58BFF54A4378F4_85788908` (`client_id`),
  KEY `FK_58BFF54A437DF4_36189800` (`data_client_id`),
  KEY `FK_58BFF54A438278_85315447` (`entity_id`),
  KEY `FK_58BFF54A4386E7_78078859` (`dataentity_id`),
  KEY `IDX_58BFF54A589859_32049709` (`code`) USING BTREE,
  KEY `IDX_58BFF54A589E21_82290673` (`description`) USING BTREE,
  CONSTRAINT `FK_58BFF54A4378F4_85788908` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_58BFF54A437DF4_36189800` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_58BFF54A438278_85315447` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_58BFF54A4386E7_78078859` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_system` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F573D4F3084B2_91877318` (`code`) USING BTREE,
  KEY `IDX_5F573D4F308689_35819852` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_system_systemmodule` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F573DA1BF8395_18530118` (`code`) USING BTREE,
  KEY `IDX_5F573DA1BF8569_88509639` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systembuild` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F5A29CB1DAC35_91112700` (`code`) USING BTREE,
  KEY `IDX_5F5A29CB1DB0C3_71159548` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systembuildtask` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F719EB8900E59_47180013` (`code`) USING BTREE,
  KEY `IDX_5F719EB89010D9_82033344` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemflagtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_646CAAF37BDF06_29598709` (`code`) USING BTREE,
  KEY `IDX_646CAAF37BDFF6_37060975` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemflagvalue` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_643F94DFB59280_87285477` (`code`) USING BTREE,
  KEY `IDX_643F94DFB59359_61527879` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemform` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_h_systemform_client_id` (`client_id`),
  KEY `FK_h_systemform_data_client_id` (`data_client_id`),
  KEY `FK_h_systemform_entity_id` (`entity_id`),
  KEY `FK_h_systemform_dataentity_id` (`dataentity_id`),
  KEY `IDX_h_systemform_code` (`code`) USING BTREE,
  KEY `IDX_h_systemform_description` (`description`) USING BTREE,
  CONSTRAINT `FK_h_systemform_client_id` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_systemform_data_client_id` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_h_systemform_dataentity_id` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_h_systemform_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemmodule` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F573D762752A0_01786296` (`code`) USING BTREE,
  KEY `IDX_5F573D76275493_39206240` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemmodule_entity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F573E5E00F689_72952920` (`code`) USING BTREE,
  KEY `IDX_5F573E5E00F845_65454467` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemmodule_navitem` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F5F1F7F25D962_89053892` (`code`) USING BTREE,
  KEY `IDX_5F5F1F7F25DBF0_69790397` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemmoduletask` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F719E8FE0B892_83160674` (`code`) USING BTREE,
  KEY `IDX_5F719E8FE0BB20_33309197` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_systemreport` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_62347E664E2846_51329423` (`code`) USING BTREE,
  KEY `IDX_62347E664E2B69_19594727` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_theme` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EF5E32BE93021_15190832` (`code`) USING BTREE,
  KEY `IDX_5EF5E32BE93412_74627360` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_todo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6007CD46242182_05160425` (`code`) USING BTREE,
  KEY `IDX_6007CD46242350_17111477` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_todostatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6007CD3AE2C009_83174467` (`code`) USING BTREE,
  KEY `IDX_6007CD3AE2C1D3_20215234` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transaction` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BD8C683A81_26245598` (`code`) USING BTREE,
  KEY `IDX_5F02BD8C683D08_84322702` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transactionhistory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_643D1738EACB98_70541507` (`code`) USING BTREE,
  KEY `IDX_643D1738EACC79_77712322` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transactionline` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BD9DD5D552_41299914` (`code`) USING BTREE,
  KEY `IDX_5F02BD9DD5D7D9_55029293` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transactionlinehistory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_643D1777C5D454_92725411` (`code`) USING BTREE,
  KEY `IDX_643D1777C5D537_60675840` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transactionlinepending` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BDD4EE7B23_02859851` (`code`) USING BTREE,
  KEY `IDX_5F02BDD4EE7E54_68740593` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transactionpending` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BDBD199BD0_82393208` (`code`) USING BTREE,
  KEY `IDX_5F02BDBD199F09_96170233` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_transactionstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02BDF7A52059_71740352` (`code`) USING BTREE,
  KEY `IDX_5F02BDF7A52394_42654616` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_user` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229C847E79D7_76070515` (`client_id`),
  KEY `FK_59229C847E7C02_22839889` (`data_client_id`),
  KEY `FK_59229C847E7DD2_27784902` (`entity_id`),
  KEY `FK_59229C847E7FA7_20280686` (`dataentity_id`),
  KEY `IDX_59229C84833DF0_31488054` (`code`) USING BTREE,
  KEY `IDX_59229C84834092_06243840` (`description`) USING BTREE,
  CONSTRAINT `FK_59229C847E79D7_76070515` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C847E7C02_22839889` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229C847E7DD2_27784902` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229C847E7FA7_20280686` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_user_branch` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_66596F09A36259_72756187` (`code`) USING BTREE,
  KEY `IDX_66596F09A36330_74323652` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_user_profile` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59229FC233EEC5_36917874` (`client_id`),
  KEY `FK_59229FC233F192_57543272` (`data_client_id`),
  KEY `FK_59229FC233F410_15361670` (`entity_id`),
  KEY `FK_59229FC233F690_65154843` (`dataentity_id`),
  KEY `IDX_59229FC238A927_55757145` (`code`) USING BTREE,
  KEY `IDX_59229FC238AC76_32955662` (`description`) USING BTREE,
  CONSTRAINT `FK_59229FC233EEC5_36917874` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229FC233F192_57543272` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59229FC233F410_15361670` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59229FC233F690_65154843` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_userform_device` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_5D7F30CCC27049_52707549` (`client_id`),
  KEY `FK_5D7F30CCC27049_51924695` (`data_client_id`),
  KEY `FK_5D7F30CCC27047_53969857` (`entity_id`),
  KEY `FK_5D7F30CCC27045_07545023` (`dataentity_id`),
  KEY `IDX_5D7F30CCE84855_41032821` (`code`) USING BTREE,
  KEY `IDX_5D7F30CCE886D9_57346179` (`description`) USING BTREE,
  CONSTRAINT `FK_5D7F30CCC27045_07545023` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D7F30CCC27047_53969857` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_5D7F30CCC27049_51924695` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_5D7F30CCC27049_52707549` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_usersetting` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59AD51AABCEFE0_63470808` (`client_id`),
  KEY `FK_59AD51AABCF318_17824811` (`data_client_id`),
  KEY `FK_59AD51AABCF578_58134637` (`entity_id`),
  KEY `FK_59AD51AABCF7C3_55136787` (`dataentity_id`),
  KEY `IDX_59AD51AAC20AC3_20891691` (`code`) USING BTREE,
  KEY `IDX_59AD51AAC20E52_55969307` (`description`) USING BTREE,
  CONSTRAINT `FK_59AD51AABCEFE0_63470808` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59AD51AABCF318_17824811` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59AD51AABCF578_58134637` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59AD51AABCF7C3_55136787` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_userstatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_59BEB1CB3B6E47_84377124` (`client_id`),
  KEY `FK_59BEB1CB3B7144_29170521` (`data_client_id`),
  KEY `FK_59BEB1CB3B7399_79537345` (`entity_id`),
  KEY `FK_59BEB1CB3B75D8_11319626` (`dataentity_id`),
  KEY `IDX_59BEB1CB40E1E4_99778144` (`code`) USING BTREE,
  KEY `IDX_59BEB1CB40E632_56232534` (`description`) USING BTREE,
  CONSTRAINT `FK_59BEB1CB3B6E47_84377124` FOREIGN KEY (`client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59BEB1CB3B7144_29170521` FOREIGN KEY (`data_client_id`) REFERENCES `d_client` (`id`),
  CONSTRAINT `FK_59BEB1CB3B7399_79537345` FOREIGN KEY (`entity_id`) REFERENCES `d_entity` (`id`),
  CONSTRAINT `FK_59BEB1CB3B75D8_11319626` FOREIGN KEY (`dataentity_id`) REFERENCES `d_entity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_video` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EE9B890184237_96854063` (`code`) USING BTREE,
  KEY `IDX_5EE9B8901844C1_21012634` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_videocategory` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EE9B847927794_76029392` (`code`) USING BTREE,
  KEY `IDX_5EE9B847927BE1_14567504` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_videosource` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5EE9B8047B32F7_60931932` (`code`) USING BTREE,
  KEY `IDX_5EE9B8047B3767_48476836` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_workqueue` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6050556F037089_03812374` (`code`) USING BTREE,
  KEY `IDX_6050556F037257_73304296` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_workqueueitemtype` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F02C5C1107FB7_15940627` (`code`) USING BTREE,
  KEY `IDX_5F02C5C1108223_73595057` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_workqueuestatus` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6050555D075765_47210441` (`code`) USING BTREE,
  KEY `IDX_6050555D075910_72924532` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_xmit_gender` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6006782E27E984_23769840` (`code`) USING BTREE,
  KEY `IDX_6006782E27EBA2_04153403` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

CREATE TABLE `h_xmit_suburb` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `entitydata_id` bigint NOT NULL,
  `client_id` bigint NOT NULL,
  `entity_id` bigint NOT NULL,
  `dataentity_id` bigint NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_enabled` varchar(1) DEFAULT NULL,
  `data_client_id` bigint NOT NULL,
  `jsondata` mediumblob,
  `modifyuser` varchar(255) NOT NULL,
  `modifydatetime` varchar(19) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60E29D8C249565_03989835` (`code`) USING BTREE,
  KEY `IDX_60E29D8C2498F1_10568077` (`description`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

