-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2025 at 04:10 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analyticsreports`
--

CREATE TABLE `analyticsreports` (
  `ReportID` int(11) NOT NULL,
  `AdminID` int(11) NOT NULL,
  `ReportData` text NOT NULL,
  `GeneratedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emaillogs`
--

CREATE TABLE `emaillogs` (
  `EmailID` int(11) NOT NULL,
  `RecipientEmail` varchar(100) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Body` text NOT NULL,
  `SentAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itpersonnel`
--

CREATE TABLE `itpersonnel` (
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Message` text NOT NULL,
  `IsRead` tinyint(1) DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `UserID` int(11) NOT NULL,
  `StudentID` varchar(20) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `School` enum('School of Computing and Informatics','School of Business and Social Sciences','School of Education and Human Sciences','Centre for Foundation and General Studies','Language Centre') NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticketassignments`
--

CREATE TABLE `ticketassignments` (
  `AssignmentID` int(11) NOT NULL,
  `TicketID` int(11) NOT NULL,
  `AssignedTo` int(11) NOT NULL,
  `AssignedBy` int(11) NOT NULL,
  `AssignedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticketcategories`
--

CREATE TABLE `ticketcategories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticketcategories`
--

INSERT INTO `ticketcategories` (`CategoryID`, `CategoryName`) VALUES
(2, 'LMS Moodle Issue'),
(5, 'Other IT Related Issues'),
(4, 'Reset Student Email'),
(3, 'Student Hub Request'),
(1, 'Wifi Issues');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `TicketID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Summary` text NOT NULL,
  `AttachmentPath` varchar(255) DEFAULT NULL,
  `Status` enum('submitted','opened','in_progress','closed') DEFAULT 'submitted',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `ClosedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticketstatushistory`
--

CREATE TABLE `ticketstatushistory` (
  `StatusID` int(11) NOT NULL,
  `TicketID` int(11) NOT NULL,
  `Status` enum('submitted','opened','in_progress','closed') NOT NULL,
  `ChangedBy` int(11) NOT NULL,
  `ChangedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('student','it_personnel','admin') NOT NULL,
  `IsVerified` tinyint(1) DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `analyticsreports`
--
ALTER TABLE `analyticsreports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `AdminID` (`AdminID`);

--
-- Indexes for table `emaillogs`
--
ALTER TABLE `emaillogs`
  ADD PRIMARY KEY (`EmailID`);

--
-- Indexes for table `itpersonnel`
--
ALTER TABLE `itpersonnel`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`);

--
-- Indexes for table `ticketassignments`
--
ALTER TABLE `ticketassignments`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `TicketID` (`TicketID`),
  ADD KEY `AssignedTo` (`AssignedTo`),
  ADD KEY `AssignedBy` (`AssignedBy`);

--
-- Indexes for table `ticketcategories`
--
ALTER TABLE `ticketcategories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryName` (`CategoryName`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`TicketID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `ticketstatushistory`
--
ALTER TABLE `ticketstatushistory`
  ADD PRIMARY KEY (`StatusID`),
  ADD KEY `TicketID` (`TicketID`),
  ADD KEY `ChangedBy` (`ChangedBy`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analyticsreports`
--
ALTER TABLE `analyticsreports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emaillogs`
--
ALTER TABLE `emaillogs`
  MODIFY `EmailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticketassignments`
--
ALTER TABLE `ticketassignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticketcategories`
--
ALTER TABLE `ticketcategories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `TicketID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticketstatushistory`
--
ALTER TABLE `ticketstatushistory`
  MODIFY `StatusID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `analyticsreports`
--
ALTER TABLE `analyticsreports`
  ADD CONSTRAINT `analyticsreports_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `admins` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `itpersonnel`
--
ALTER TABLE `itpersonnel`
  ADD CONSTRAINT `itpersonnel_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `ticketassignments`
--
ALTER TABLE `ticketassignments`
  ADD CONSTRAINT `ticketassignments_ibfk_1` FOREIGN KEY (`TicketID`) REFERENCES `tickets` (`TicketID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticketassignments_ibfk_2` FOREIGN KEY (`AssignedTo`) REFERENCES `itpersonnel` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticketassignments_ibfk_3` FOREIGN KEY (`AssignedBy`) REFERENCES `admins` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `ticketcategories` (`CategoryID`) ON DELETE CASCADE;

--
-- Constraints for table `ticketstatushistory`
--
ALTER TABLE `ticketstatushistory`
  ADD CONSTRAINT `ticketstatushistory_ibfk_1` FOREIGN KEY (`TicketID`) REFERENCES `tickets` (`TicketID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticketstatushistory_ibfk_2` FOREIGN KEY (`ChangedBy`) REFERENCES `itpersonnel` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
