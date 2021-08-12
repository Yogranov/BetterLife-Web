-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2021 at 07:35 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `betterlife`
--

-- --------------------------------------------------------

--
-- Table structure for table `articlecomms`
--

CREATE TABLE `articlecomms` (
  `Id` int(11) NOT NULL,
  `ArticleId` int(11) NOT NULL,
  `Content` text COLLATE utf8_unicode_ci NOT NULL,
  `Creator` int(11) NOT NULL,
  `Likes` text COLLATE utf8_unicode_ci NOT NULL,
  `CreateTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `Id` int(11) NOT NULL,
  `Title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ImgUrl` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Content` text COLLATE utf8_unicode_ci NOT NULL,
  `Creator` int(11) NOT NULL,
  `Publish` tinyint(4) NOT NULL,
  `Likes` text COLLATE utf8_unicode_ci NOT NULL,
  `Views` int(11) NOT NULL,
  `CreateTime` datetime NOT NULL,
  `LastUpdate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `Id` int(11) NOT NULL,
  `HebrewName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `EnglishName` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conmessages`
--

CREATE TABLE `conmessages` (
  `Id` int(11) NOT NULL,
  `ConversationId` int(11) NOT NULL,
  `CreatorId` int(11) NOT NULL,
  `Content` text COLLATE utf8_unicode_ci NOT NULL,
  `CreateTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `Id` int(11) NOT NULL,
  `CreatorId` int(11) NOT NULL,
  `RecipientId` int(11) NOT NULL,
  `Subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Views` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreateTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cookies`
--

CREATE TABLE `cookies` (
  `UserId` int(11) NOT NULL,
  `Hash` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `UserId` int(11) NOT NULL,
  `LicenseNumber` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `About` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loginattempts`
--

CREATE TABLE `loginattempts` (
  `Ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Attempts` tinyint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Status` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Log` text COLLATE utf8_unicode_ci NOT NULL,
  `Timestamp` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moledetails`
--

CREATE TABLE `moledetails` (
  `Id` int(11) NOT NULL,
  `MoleId` int(11) NOT NULL,
  `ImgUrl` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ImgFigureUrl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ImgSurfaceUrl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Size` int(11) NOT NULL,
  `Color` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `BenignPred` float DEFAULT NULL,
  `MalignantPred` float DEFAULT NULL,
  `CreateTime` datetime NOT NULL,
  `DoctorId` int(11) DEFAULT NULL,
  `RiskLevel` tinyint(4) NOT NULL,
  `Diagnosis` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `DiagnosisCreateTime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moles`
--

CREATE TABLE `moles` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Location` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Removed` tinyint(4) DEFAULT NULL,
  `CreateTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qrcodes`
--

CREATE TABLE `qrcodes` (
  `Id` int(11) NOT NULL,
  `UserIp` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `QrCode` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `UserToken` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `risklevel`
--

CREATE TABLE `risklevel` (
  `Id` int(11) NOT NULL,
  `Name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `Id` int(11) NOT NULL,
  `Name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `PersonId` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `FirstName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `LastName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Sex` tinyint(1) NOT NULL DEFAULT 0,
  `PhoneNumber` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `BirthDate` datetime NOT NULL,
  `Address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Roles` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `HaveHistory` tinyint(4) DEFAULT NULL,
  `RegisterTime` datetime NOT NULL,
  `LastLogin` datetime DEFAULT NULL,
  `RecoverToken` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Token` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Enable` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articlecomms`
--
ALTER TABLE `articlecomms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `conmessages`
--
ALTER TABLE `conmessages`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `moledetails`
--
ALTER TABLE `moledetails`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `moles`
--
ALTER TABLE `moles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `risklevel`
--
ALTER TABLE `risklevel`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articlecomms`
--
ALTER TABLE `articlecomms`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conmessages`
--
ALTER TABLE `conmessages`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moledetails`
--
ALTER TABLE `moledetails`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moles`
--
ALTER TABLE `moles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qrcodes`
--
ALTER TABLE `qrcodes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `risklevel`
--
ALTER TABLE `risklevel`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
