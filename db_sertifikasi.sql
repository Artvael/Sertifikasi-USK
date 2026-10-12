-- Database: `db_sertifikasi`
-- Sistem Informasi Pengelolaan Data Peserta Sertifikasi

CREATE DATABASE IF NOT EXISTS `db_sertifikasi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_sertifikasi`;

-- --------------------------------------------------------

-- Struktur dari tabel `admin`
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data untuk tabel `admin`
INSERT INTO `admin` (`id`, `username`, `password`, `nama`) VALUES
(1, 'admin', 'admin123', 'Victor');

-- --------------------------------------------------------

-- Struktur dari tabel `skema`
CREATE TABLE IF NOT EXISTS `skema` (
  `id_skema` int(11) NOT NULL AUTO_INCREMENT,
  `kode_skema` varchar(20) NOT NULL,
  `nama_skema` varchar(100) NOT NULL,
  PRIMARY KEY (`id_skema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data untuk tabel `skema`
INSERT INTO `skema` (`id_skema`, `kode_skema`, `nama_skema`) VALUES
(2, 'SKM-JWD-02', 'Medium Web Dev'),
(3, 'SKM-JWD-03', 'Expert Web Developer');

-- --------------------------------------------------------

-- Struktur dari tabel `peserta`
CREATE TABLE IF NOT EXISTS `peserta` (
  `id_peserta` int(11) NOT NULL AUTO_INCREMENT,
  `nama_peserta` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `id_skema` int(11) NOT NULL,
  PRIMARY KEY (`id_peserta`),
  KEY `id_skema` (`id_skema`),
  CONSTRAINT `fk_peserta_skema` FOREIGN KEY (`id_skema`) REFERENCES `skema` (`id_skema`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data untuk tabel `peserta`
INSERT INTO `peserta` (`id_peserta`, `nama_peserta`, `email`, `telepon`, `id_skema`) VALUES
(1, 'Miko', 'Mikoangelo@gmail.com', '087890208776', 2),
(2, 'Dwi', 'Dwi@gmail.com', '087820908665', 2),
(3, 'Maydixiun', 'Cison@gmail.com', '0199288281821', 3),
(4, 'maydixiun', 'Cison@gmail.com', '087820908665', 2);
