SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `partidos`;
CREATE TABLE IF NOT EXISTS `partidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `local` varchar(100) NOT NULL,
  `visitante` varchar(100) NOT NULL,
  `estadio` varchar(100) NOT NULL,
  `fechahora` datetime NOT NULL,
  `reslocal` int(11) NOT NULL,
  `resvisitante` int(11) NOT NULL,
  `grouporder` int(11) NOT NULL,
  `matchorder` int(11) NOT NULL,
  `grupo` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

DROP TABLE IF EXISTS `resultados`;
CREATE TABLE IF NOT EXISTS `resultados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partido` int(11) NOT NULL,
  `local` int(11) NOT NULL,
  `visitante` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `puntos` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partido` (`partido`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=127 ;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `oauth_token` varchar(100) NOT NULL,
  `oauth_token_secret` varchar(100) NOT NULL,
  `puntos` int(11) NOT NULL,
  `loginerrors` int(11) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `last_login` datetime NOT NULL,
  `registered` datetime NOT NULL,
  `twitter_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `twitter_id` (`twitter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=144 ;
