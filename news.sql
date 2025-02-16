-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2025 at 11:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `halaman`
--

DROP TABLE IF EXISTS `halaman`;
CREATE TABLE `halaman` (
  `id` int NOT NULL,
  `penulis` varchar(50) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `kutipan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `isi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `gambar` varchar(50) DEFAULT NULL,
  `tgl_isi` timestamp NULL DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `halaman`
--

INSERT INTO `halaman` (`id`, `penulis`, `judul`, `kutipan`, `isi`, `gambar`, `tgl_isi`, `kategori`) VALUES
(10, 'Angga Nugraha', 'Evolusi Teknologi Web: Dari Statis ke Dinamis', 'Teknologi web terus berkembang dari waktu ke waktu, memberikan pengalaman digital yang semakin interaktif. Pada awalnya, situs web bersifat statis, hanya berisi teks dan gambar yang tidak dapat berubah tanpa pembaruan manual oleh pengembang. Namun, dengan munculnya teknologi seperti JavaScript, PHP, dan AJAX, situs web menjadi lebih dinamis dan responsif.', 'Teknologi web terus berkembang dari waktu ke waktu, memberikan pengalaman digital yang semakin interaktif. Pada awalnya, situs web bersifat statis, hanya berisi teks dan gambar yang tidak dapat berubah tanpa pembaruan manual oleh pengembang. Namun, dengan munculnya teknologi seperti JavaScript, PHP, dan AJAX, situs web menjadi lebih dinamis dan responsif.\r\n\r\nSaat ini, framework seperti React, Angular, dan Vue.js mendominasi pengembangan front-end, memungkinkan pengalaman pengguna yang lebih interaktif. Di sisi back-end, teknologi seperti Node.js, Django, dan Laravel mempercepat proses pengembangan dan manajemen data.\r\n\r\nSelain itu, tren seperti Progressive Web Apps (PWA) dan WebAssembly (Wasm) semakin memperluas kemampuan web. Menurut Tim Berners-Lee, penemu World Wide Web, \"Web adalah tentang keterbukaan dan inovasi.\" (kutipan: 11 kata)\r\n\r\nKeamanan juga menjadi perhatian utama dalam perkembangan teknologi web. Penerapan HTTPS, autentikasi dua faktor, dan enkripsi data semakin banyak digunakan untuk melindungi pengguna dari ancaman siber.\r\n\r\nDengan perkembangan teknologi cloud dan kecerdasan buatan, masa depan web diprediksi akan semakin canggih dan memberikan pengalaman yang lebih personal serta efisien bagi penggunanya.\r\n\r\n', '67b0753d32afa.png', '2023-06-09 22:27:39', 'Web'),
(24, 'Diki Faturrohman', 'Evolusi Mobile Development: Dari Aplikasi Sederhana ke AI-Powered Apps', 'Mobile development telah berkembang pesat, menghadirkan aplikasi yang semakin canggih dan inovatif. Awalnya, aplikasi mobile bersifat sederhana dan terbatas pada fungsi dasar. Namun, dengan munculnya sistem operasi seperti Android dan iOS, serta bahasa pemrograman seperti Java, Swift, dan Kotlin, pengembangan aplikasi menjadi lebih fleksibel dan efisien.', 'Mobile development telah berkembang pesat, menghadirkan aplikasi yang semakin canggih dan inovatif. Awalnya, aplikasi mobile bersifat sederhana dan terbatas pada fungsi dasar. Namun, dengan munculnya sistem operasi seperti Android dan iOS, serta bahasa pemrograman seperti Java, Swift, dan Kotlin, pengembangan aplikasi menjadi lebih fleksibel dan efisien.\r\n\r\nSaat ini, framework seperti Flutter dan React Native memungkinkan pengembangan aplikasi lintas platform dengan kode yang dapat digunakan kembali. Selain itu, teknologi kecerdasan buatan (AI) semakin banyak diintegrasikan untuk meningkatkan pengalaman pengguna.\r\n\r\nTren terbaru dalam mobile development mencakup aplikasi berbasis augmented reality (AR), Internet of Things (IoT), dan machine learning. Menurut Sundar Pichai, CEO Google, \"Masa depan teknologi adalah kecerdasan buatan dan mobile.\" (kutipan: 11 kata)\r\n\r\nKeamanan dan privasi juga menjadi perhatian utama dalam pengembangan aplikasi mobile. Penerapan enkripsi data, autentikasi biometrik, dan kepatuhan terhadap regulasi seperti GDPR semakin penting untuk melindungi pengguna.\r\n\r\nDengan perkembangan cloud computing dan 5G, mobile development diprediksi akan semakin maju, menghadirkan pengalaman yang lebih cepat, cerdas, dan personal bagi pengguna di masa depan.', '67b0753257e53.png', '2025-02-15 11:06:26', 'Mobile'),
(25, 'Fadhil Rizky', 'Evolusi Game Development: Dari Piksel ke Realitas Virtual', 'Game development telah mengalami perkembangan pesat sejak era game 8-bit hingga teknologi realitas virtual saat ini. Awalnya, game dikembangkan dengan grafis sederhana dan mekanisme permainan yang terbatas. Namun, dengan kemajuan perangkat keras dan perangkat lunak, game kini memiliki visual yang lebih realistis dan gameplay yang lebih kompleks.', 'Game development telah mengalami perkembangan pesat sejak era game 8-bit hingga teknologi realitas virtual saat ini. Awalnya, game dikembangkan dengan grafis sederhana dan mekanisme permainan yang terbatas. Namun, dengan kemajuan perangkat keras dan perangkat lunak, game kini memiliki visual yang lebih realistis dan gameplay yang lebih kompleks.\r\n\r\nEngine seperti Unity dan Unreal Engine memungkinkan pengembang menciptakan pengalaman imersif dengan dukungan grafis tinggi dan fisika yang lebih realistis. Selain itu, pengembangan game kini semakin dipermudah dengan adanya alat seperti Godot dan RPG Maker yang memungkinkan pembuatan game tanpa perlu banyak kode.\r\n\r\nTren terbaru dalam game development meliputi teknologi cloud gaming, kecerdasan buatan (AI) dalam desain game, serta integrasi augmented reality (AR) dan virtual reality (VR). Menurut John Carmack, salah satu pionir industri game, \"Masa depan game adalah tentang pengalaman yang lebih mendalam dan interaktif.\" (kutipan: 13 kata)\r\n\r\nKeamanan dalam game juga menjadi perhatian utama, terutama dalam game online yang rentan terhadap peretasan dan kecurangan. Implementasi sistem anti-cheat dan enkripsi data menjadi penting untuk menjaga ekosistem game yang adil.\r\n\r\nDengan perkembangan teknologi AI, blockchain, dan 5G, masa depan game development diprediksi akan semakin canggih, memberikan pengalaman bermain yang lebih personal dan imersif bagi para pemain di seluruh dunia.\r\n\r\n', '67b075a427017.png', '2025-02-15 11:08:20', 'Game'),
(26, 'Zabihullah', 'Evolusi UI/UX: Dari Desain Sederhana ke Pengalaman Imersif', 'UI/UX (User Interface dan User Experience) telah berkembang pesat, memberikan pengalaman digital yang lebih intuitif dan menarik bagi pengguna. Awalnya, desain antarmuka hanya berfokus pada fungsi dasar, tetapi kini aspek estetika dan pengalaman pengguna menjadi faktor utama dalam pengembangan produk digital.', 'UI/UX (User Interface dan User Experience) telah berkembang pesat, memberikan pengalaman digital yang lebih intuitif dan menarik bagi pengguna. Awalnya, desain antarmuka hanya berfokus pada fungsi dasar, tetapi kini aspek estetika dan pengalaman pengguna menjadi faktor utama dalam pengembangan produk digital.\r\n\r\nFramework dan alat seperti Figma, Adobe XD, dan Sketch mempermudah desainer dalam menciptakan prototipe interaktif. Selain itu, tren desain seperti dark mode, micro-interactions, dan desain responsif semakin populer untuk meningkatkan kenyamanan pengguna.\r\n\r\nPrinsip utama dalam UI/UX adalah memahami kebutuhan pengguna dan menciptakan pengalaman yang intuitif. Menurut Don Norman, seorang pakar UX, \"Desain yang baik adalah ketika seseorang tidak perlu berpikir keras untuk menggunakannya.\" (kutipan: 13 kata)\r\n\r\nKeamanan dan aksesibilitas juga menjadi aspek penting dalam UI/UX, memastikan bahwa semua pengguna, termasuk mereka dengan kebutuhan khusus, dapat mengakses dan menggunakan aplikasi dengan mudah.\r\n\r\nDengan kemajuan teknologi AI dan realitas virtual, UI/UX diprediksi akan semakin interaktif dan personal, menghadirkan pengalaman digital yang lebih mendalam dan menyenangkan bagi pengguna.\r\n\r\n', '67b076af5ce9c.png', '2025-02-15 11:12:47', 'UI/UX'),
(27, 'Adira', 'Teknologi Web: Tren dan Masa Depan', 'Teknologi web terus mengalami inovasi, menciptakan ekosistem digital yang lebih cepat, aman, dan interaktif. Pada era awal, situs web bersifat statis dan terbatas dalam fungsionalitasnya. Namun, perkembangan seperti JavaScript, CSS, dan HTML5 memungkinkan pembuatan situs yang lebih dinamis dan responsif.', 'Teknologi web terus mengalami inovasi, menciptakan ekosistem digital yang lebih cepat, aman, dan interaktif. Pada era awal, situs web bersifat statis dan terbatas dalam fungsionalitasnya. Namun, perkembangan seperti JavaScript, CSS, dan HTML5 memungkinkan pembuatan situs yang lebih dinamis dan responsif.\r\n\r\nFramework modern seperti React, Angular, dan Vue.js memberikan efisiensi dalam pengembangan antarmuka pengguna, sementara teknologi server-side seperti Node.js, Django, dan Laravel mempercepat proses pengolahan data. Selain itu, konsep API-first dan microservices semakin diadopsi untuk meningkatkan skalabilitas dan fleksibilitas sistem.\r\n\r\nTren seperti Progressive Web Apps (PWA), WebAssembly (Wasm), dan integrasi kecerdasan buatan (AI) mengubah cara pengguna berinteraksi dengan web. Menurut Tim Berners-Lee, \"Web adalah alat yang dirancang untuk membantu manusia berbagi informasi dan berkolaborasi.\" (kutipan: 15 kata)\r\n\r\nKeamanan juga menjadi aspek krusial dalam teknologi web modern. Implementasi HTTPS, sistem autentikasi yang lebih kuat, serta perlindungan terhadap serangan siber menjadi standar dalam pengembangan aplikasi web.\r\n\r\nKe depan, teknologi web diprediksi akan semakin terintegrasi dengan realitas virtual (VR), Internet of Things (IoT), dan edge computing, menciptakan pengalaman digital yang lebih kaya dan lebih efisien bagi pengguna di seluruh dunia.\r\n\r\n', '67b0774cc0757.png', '2025-02-15 11:15:24', 'Web'),
(28, 'Diki Faturrohman', 'Mobile Development: Masa Depan Teknologi Aplikasi', 'Di era digital saat ini, mobile development menjadi salah satu bidang teknologi yang berkembang pesat. Dengan meningkatnya jumlah pengguna smartphone, permintaan akan aplikasi mobile juga semakin tinggi.', 'Di era digital saat ini, mobile development menjadi salah satu bidang teknologi yang berkembang pesat. Dengan meningkatnya jumlah pengguna smartphone, permintaan akan aplikasi mobile juga semakin tinggi.\r\n\r\nApa Itu Mobile Development?\r\nMobile development adalah proses pembuatan aplikasi untuk perangkat seluler seperti Android dan iOS. Aplikasi ini dapat berupa native apps (dikembangkan khusus untuk platform tertentu), web apps, atau hybrid apps (kombinasi native dan web).\r\n\r\nTeknologi dalam Mobile Development\r\nBeberapa teknologi populer dalam pengembangan aplikasi mobile meliputi:\r\n\r\nFlutter: Framework dari Google untuk membuat aplikasi lintas platform dengan satu basis kode.\r\nReact Native: Framework berbasis JavaScript yang memungkinkan pengembangan aplikasi Android dan iOS dengan kode yang sama.\r\nKotlin & Swift: Bahasa pemrograman utama untuk aplikasi Android (Kotlin) dan iOS (Swift).\r\nTantangan dan Peluang\r\nTantangan utama dalam mobile development adalah optimasi performa, keamanan data, dan kesesuaian dengan berbagai perangkat. Namun, dengan perkembangan teknologi, peluang di bidang ini juga semakin besar, terutama di industri e-commerce, fintech, dan gaming.\r\n\r\nKesimpulan\r\nMobile development terus berkembang dan menjadi bagian penting dari transformasi digital. Dengan menguasai teknologi yang tepat, peluang karier di bidang ini sangat menjanjikan. 🚀\r\n\r\n', '67b1af2a0da99.png', '2025-02-16 09:26:02', 'Mobile'),
(29, 'Fadhil Rizky', 'Game Development: Kreativitas dan Teknologi dalam Satu Paket', 'Game development adalah proses pembuatan permainan digital yang melibatkan berbagai aspek, seperti desain, pemrograman, audio, dan animasi. Industri ini terus berkembang seiring dengan meningkatnya popularitas game di berbagai platform, termasuk PC, konsol, dan mobile.', 'Game Development: Kreativitas dan Teknologi dalam Satu Paket\r\nGame development adalah proses pembuatan permainan digital yang melibatkan berbagai aspek, seperti desain, pemrograman, audio, dan animasi. Industri ini terus berkembang seiring dengan meningkatnya popularitas game di berbagai platform, termasuk PC, konsol, dan mobile.\r\n\r\nLangkah-Langkah dalam Game Development\r\nKonsep & Perancangan – Ide awal game dibuat, termasuk mekanisme permainan, alur cerita, dan desain karakter.\r\nPemrograman – Menggunakan engine seperti Unity (C#) atau Unreal Engine (C++) untuk mengembangkan gameplay dan sistem permainan.\r\nDesain Grafis & Animasi – Membuat karakter, lingkungan, dan efek visual menggunakan software seperti Blender atau Photoshop.\r\nAudio & Musik – Efek suara dan musik ditambahkan untuk meningkatkan pengalaman bermain.\r\nTesting & Rilis – Game diuji untuk memastikan tidak ada bug sebelum dirilis ke publik.\r\nTantangan dan Peluang\r\nGame development membutuhkan kreativitas dan keterampilan teknis yang tinggi. Tantangan utamanya meliputi pengoptimalan performa, keseimbangan gameplay, dan monetisasi. Namun, peluang di industri ini sangat besar, terutama dengan meningkatnya permintaan untuk game indie dan mobile games.\r\n\r\nKesimpulan\r\nDengan berkembangnya teknologi seperti VR dan AI, masa depan game development semakin menarik. Bagi yang ingin terjun ke dunia ini, belajar coding, desain, dan storytelling adalah langkah awal yang penting. 🎮🔥\r\n\r\n', '67b1af67ca83c.png', '2025-02-16 09:27:03', 'Game'),
(30, 'Adira', 'UI/UX: Kunci Pengalaman Pengguna yang Optimal', 'Dalam dunia digital, User Interface (UI) dan User Experience (UX) adalah dua elemen penting dalam desain produk. UI berfokus pada tampilan visual dan interaksi pengguna, sementara UX berkaitan dengan bagaimana pengguna merasakan dan berinteraksi dengan produk secara keseluruhan.', 'Dalam dunia digital, User Interface (UI) dan User Experience (UX) adalah dua elemen penting dalam desain produk. UI berfokus pada tampilan visual dan interaksi pengguna, sementara UX berkaitan dengan bagaimana pengguna merasakan dan berinteraksi dengan produk secara keseluruhan.\r\n\r\nPerbedaan UI dan UX\r\nUI (User Interface): Meliputi desain warna, ikon, tata letak, dan elemen visual lainnya yang membuat aplikasi menarik dan mudah digunakan.\r\nUX (User Experience): Berhubungan dengan bagaimana pengguna berinteraksi dengan produk, termasuk kemudahan navigasi, kecepatan akses, dan kenyamanan dalam penggunaan.\r\nPrinsip Penting dalam UI/UX\r\nKonsistensi – Desain harus seragam di seluruh platform agar pengguna merasa familiar.\r\nSimpel dan Intuitif – Navigasi yang mudah dipahami akan meningkatkan pengalaman pengguna.\r\nResponsif – Tampilan harus optimal di berbagai perangkat dan ukuran layar.\r\nAksesibilitas – Desain harus dapat digunakan oleh semua orang, termasuk mereka yang memiliki keterbatasan.\r\nMengapa UI/UX Penting?\r\nDesain UI/UX yang baik dapat meningkatkan kepuasan pengguna, mengurangi tingkat kesalahan, dan meningkatkan retensi pengguna. Produk digital seperti aplikasi dan website yang memiliki UI/UX buruk cenderung ditinggalkan pengguna dalam waktu singkat.\r\n\r\nKesimpulan\r\nUI/UX bukan hanya soal estetika, tetapi juga tentang bagaimana pengguna merasa nyaman dan mendapatkan pengalaman terbaik saat menggunakan suatu produk. Dengan menerapkan prinsip UI/UX yang baik, produk digital akan lebih sukses dan diminati oleh banyak orang. 🚀', '67b1afab585c6.png', '2025-02-16 09:28:11', 'UI/UX');

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

DROP TABLE IF EXISTS `komentar`;
CREATE TABLE `komentar` (
  `id` int NOT NULL,
  `halaman_id` int NOT NULL,
  `nama` varchar(100) NOT NULL DEFAULT 'Anonim',
  `komentar` text NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id`, `halaman_id`, `nama`, `komentar`, `tanggal`) VALUES
(19, 10, 'Anonim', 'Halo, artikel ini sangat bagus!', '2025-02-16 06:09:39'),
(20, 10, 'Anonim', 'Ternyata dulu web begitu sederhana!', '2025-02-16 06:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(6, 'admin123', 'Admin123@gmail.com', '$2y$10$O2.nqkfonKYuhJSi5mF5O.y7JR6iwW0GSQ3xfrYP/fsoMoO69KBpe', 'admin'),
(18, 'diki faturrohman', 'dikifaturrohman17@gmail.com', '$2y$10$X8cUgvUyGmMf88YZRopaEO/16kzVinTx.8KNFQJ39MeWHD4ZcJ2m.', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `halaman`
--
ALTER TABLE `halaman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `halaman_id` (`halaman_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `halaman`
--
ALTER TABLE `halaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
