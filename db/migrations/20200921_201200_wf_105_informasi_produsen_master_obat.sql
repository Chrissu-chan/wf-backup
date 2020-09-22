CREATE TABLE `produsen` (
  `id` int(11) NOT NULL,
  `nama_produsen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produsen`
--

INSERT INTO `produsen` (`id`, `nama_produsen`) VALUES
(1, 'FRENDY'),
(2, 'ADYT PAKE p'),
(3, 'ZEIN'),
(4, 'DONI'),
(5, 'FIRMAN'),
(6, 'OROCHIMARU');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produsen`
--
ALTER TABLE `produsen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produsen`
--
ALTER TABLE `produsen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `pos`.`obat` 
ADD COLUMN `id_produsen` int(11) NOT NULL AFTER `total`;