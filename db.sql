DB Name : trs

--

-- Table structure for table `student`

--

 

DROP TABLE IF EXISTS `student`;

CREATE TABLE IF NOT EXISTS `student` (

  `sid` int(10) NOT NULL AUTO_INCREMENT,

  `sname` varchar(200) NOT NULL,

  `sdob` date NOT NULL,

  `snationality` varchar(15) NOT NULL,

  `smobile` varchar(15) NOT NULL,

  PRIMARY KEY (`sid`)

) ENGINE=MyISAM DEFAULT CHARSET=latin1;

COMMIT;