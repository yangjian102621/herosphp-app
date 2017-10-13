--
-- Table structure for table `admin_menu`
--

DROP TABLE IF EXISTS `admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '父级菜单ID',
  `name` varchar(50) DEFAULT NULL COMMENT '菜单名称',
  `url` varchar(100) DEFAULT NULL COMMENT '菜单url',
  `permission` varchar(50) DEFAULT NULL COMMENT '菜单权限',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  `updatetime` datetime DEFAULT NULL COMMENT '更新时间',
  `sort` smallint(3) DEFAULT '0' COMMENT '排序数字,越大越靠后',
  `enable` smallint(1) DEFAULT '0' COMMENT '是否可用,0:禁用,1:启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='后台管理菜单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,2,'404页面','/admin/index/page404','','2017-09-03 15:14:26','2017-10-12 19:25:47',2,1),(2,0,'用户管理','','','2017-09-03 15:21:31','2017-10-13 09:46:58',1,1),(3,2,'403页面','/admin/index/page403','','2017-09-03 16:52:35','2017-10-12 20:11:47',3,1),(4,0,'系统管理员','','','2017-09-03 16:52:59','2017-09-03 16:52:59',2,1),(5,4,'管理员列表','/admin/manager/index','adminUser:list','2017-09-03 17:00:15','2017-09-03 17:00:15',1,1),(6,4,'角色列表','/admin/role/index','role:list','2017-09-03 17:32:03','2017-09-03 17:32:03',2,1),(7,4,'权限列表','/admin/permission/index','permission:list','2017-09-03 17:32:31','2017-09-03 17:32:31',3,1),(8,4,'菜单列表','/admin/menu/index','menu:list','2017-09-03 17:33:26','2017-09-03 17:33:26',4,1),(9,2,'首页','/admin/index/index','user:list','2017-09-08 14:49:06','2017-10-12 19:39:59',1,1);
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pgroup` varchar(30) DEFAULT NULL COMMENT '模块(分组)',
  `name` varchar(50) DEFAULT NULL COMMENT '权限名称',
  `pkey` varchar(50) DEFAULT NULL COMMENT '权限标识',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkey` (`pkey`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='后台管理员角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'system','管理员列表','adminUser:list','2017-08-29 10:57:28','2017-08-31 22:39:34'),(2,'system','管理员添加','adminUser:add','2017-08-29 11:21:50','2017-08-31 22:39:29'),(3,'system','管理员编辑','adminUser:edit','2017-08-29 11:22:27','2017-08-31 22:39:24'),(4,'system','管理员删除','adminUser:delete','2017-08-29 16:39:22','2017-08-31 22:39:10'),(5,'user','添加会员','user:add','2017-08-30 11:38:59','2017-08-31 23:21:19'),(6,'user','编辑会员','user:edit','2017-08-30 11:39:16','2017-08-31 23:21:11'),(7,'user','删除会员','user:delete','2017-08-30 11:39:44','2017-08-31 23:21:00'),(8,'user','审核会员','user:check','2017-08-30 11:40:00','2017-08-31 23:20:53'),(9,'user','个人会员列表','user:list','2017-08-31 22:10:52','2017-08-31 22:38:40'),(10,'user','企业会员列表','company:list','2017-08-31 22:11:11','2017-08-31 22:38:32'),(14,'user','(禁用|启用)会员','user:enable','2017-08-31 23:20:34','2017-08-31 23:20:34'),(15,'user','查看会员信息','user:view','2017-08-31 23:22:07','2017-08-31 23:22:07'),(16,'system','添加角色','role:list','2017-08-31 23:47:10','2017-08-31 23:47:10'),(17,'system','修改角色','role:edit','2017-08-31 23:47:31','2017-08-31 23:47:31'),(18,'system','删除角色','role:delete','2017-08-31 23:47:47','2017-08-31 23:47:47'),(23,'user','权限列表','permission:list','2017-09-02 17:59:36','2017-09-02 17:59:36'),(24,'system','编辑权限','permission:edit','2017-09-02 18:06:01','2017-09-02 18:06:01'),(25,'system','删除权限','permission:delete','2017-09-02 18:06:53','2017-09-02 18:06:53'),(38,'system','添加权限','permission:add','2017-09-02 19:29:51','2017-09-02 19:29:51'),(39,'system','修改影片','movie:edit','2017-09-02 19:30:11','2017-09-02 19:30:11'),(40,'system','菜单列表','menu:list','2017-09-03 22:35:36','2017-09-03 22:35:36'),(41,'system','添加菜单','menu:add','2017-09-03 22:36:04','2017-09-03 22:36:04'),(42,'system','修改菜单','menu:edit','2017-09-03 22:36:27','2017-09-03 22:36:27'),(43,'system','删除菜单','menu:delete','2017-09-03 22:36:44','2017-09-03 22:36:44');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role`
--

DROP TABLE IF EXISTS `admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `permissions` text COMMENT '角色权限，使用json存储',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  `enable` smallint(1) DEFAULT '1' COMMENT '是否可用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='后台管理员角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role`
--

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;
INSERT INTO `admin_role` VALUES (1,'超级管理员','{\"user:view\":1,\"movie:template:delete\":1,\"user:list\":1,\"adminUser:list\":1,\"permission:add\":1,\"theatre:enable\":1,\"company:list\":1,\"movie:list\":1,\"user:check\":1,\"permission:delete\":1,\"movie:publish\":1,\"adminUser:delete\":1,\"theatre:edit\":1,\"user:enable\":1,\"movie:seats:save\":1,\"role:list\":1,\"role:delete\":1,\"theatrehall:add\":1,\"permission:edit\":1,\"menu:delete\":1,\"movie:template:add\":1,\"adminUser:edit\":1,\"movie:template:list\":1,\"user:add\":1,\"user:delete\":1,\"permission:list\":1,\"theatrehall:list\":1,\"theatrehall:edit\":1,\"menu:edit\":1,\"menu:list\":1,\"movie:add\":1,\"movie:check\":1,\"theatrehall:delete\":1,\"movie:edit\":1,\"theatre:add\":1,\"theatre:delete\":1,\"menu:add\":1,\"movie:template:edit\":1,\"user:edit\":1,\"role:edit\":1,\"theatre:list\":1,\"adminUser:add\":1}','2017-08-28 15:39:45','2017-08-28 15:39:45',1),(4,'产品经理','{\"adminUser:list\":\"1\",\"adminUser:add\":\"1\",\"adminUser:edit\":\"1\",\"adminUser:delete\":\"1\",\"user:add\":\"1\",\"user:edit\":\"1\",\"user:delete\":\"1\",\"user:check\":\"1\",\"user:list\":\"1\",\"company:list\":\"1\"}','2017-08-28 15:45:41','2017-08-30 16:37:55',1),(9,'测试角色','{\"adminUser:list\":\"1\",\"adminUser:add\":\"1\",\"adminUser:edit\":\"1\",\"adminUser:delete\":\"1\"}','2017-08-30 16:06:12','2017-10-12 09:55:55',1),(10,'项目经理','{\"user::edit\":1,\"adminUser::delete\":1,\"user::delete\":1,\"user::check\":1,\"adminUser::index\":1,\"adminUser::add\":1,\"adminUser::edit\":1,\"user::add\":1}','2017-08-30 16:06:21','2017-08-30 16:38:02',1);
/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL COMMENT '用户名，使用电话号码注册',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `role_ids` varchar(255) DEFAULT NULL COMMENT '角色用户角色ID集合, list json',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `salt` varchar(32) DEFAULT NULL COMMENT '密码盐',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  `enable` int(1) DEFAULT '1' COMMENT '是否可用，1：可用，0：冻结',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(15) DEFAULT NULL COMMENT '最后登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='后台管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user`
--

LOCK TABLES `admin_user` WRITE;
/*!40000 ALTER TABLE `admin_user` DISABLE KEYS */;
INSERT INTO `admin_user` VALUES (1,'admin','杨坚','[\"1\"]','89c8ab04fe4f326f64c349b9ca9cd4ad',NULL,'2017-08-08 16:19:43','2017-08-31 22:11:40',1,'2017-08-30 23:34:55','127.0.0.1'),(4,'xiaoyang','小明','[\"1\",\"4\",\"9\"]','',NULL,'2017-08-23 15:27:29','2017-08-30 21:55:52',1,'2017-08-23 15:27:29','127.0.0.1'),(6,'admin3','千山我独行','[\"1\",\"4\",\"9\"]','3a0a66d68c76e03394b75d7c099d1b8f','d58d7ddc237e37018a946442e84d62f9','2017-08-30 23:35:42','2017-10-11 17:59:38',1,'2017-09-15 10:55:18','127.0.0.1');
/*!40000 ALTER TABLE `admin_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-13 10:20:46
