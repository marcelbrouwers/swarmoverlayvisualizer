
--
-- Table structure for table `firewall`
--

CREATE TABLE `firewall` (
  `id` int(11) NOT NULL,
  `host` varchar(255) NOT NULL,
  `container` varchar(255) NOT NULL,
  `inputpolicy` varchar(10) NOT NULL,
  `timestamp` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `ports`
--

CREATE TABLE `ports` (
  `id` int(11) NOT NULL,
  `host` varchar(255) NOT NULL,
  `container` varchar(100) NOT NULL,
  `protocol` varchar(6) NOT NULL,
  `localaddress` varchar(255) NOT NULL,
  `foreignaddress` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `timestamp` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `scan`
--

CREATE TABLE `scan` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `protocol` varchar(5) NOT NULL,
  `port` int(6) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `host` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for table `firewall`
--
ALTER TABLE `firewall`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ports`
--
ALTER TABLE `ports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scan`
--
ALTER TABLE `scan`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `firewall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `ports`
--
ALTER TABLE `ports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28881;
--
-- AUTO_INCREMENT for table `scan`
--
ALTER TABLE `scan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;