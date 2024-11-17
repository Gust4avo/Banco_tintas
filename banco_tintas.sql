-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/11/2024 às 03:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco_tintas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_tinta` int(11) NOT NULL,
  `usuario_email` varchar(255) NOT NULL,
  `finalidade` varchar(255) NOT NULL,
  `status` enum('pendente','entregue') DEFAULT 'pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `quantidade_solicitada` int(11) NOT NULL,
  `dia_retirada` varchar(20) DEFAULT NULL,
  `horario_retirada` varchar(20) DEFAULT NULL,
  `local_retirada` varchar(50) DEFAULT NULL,
  `status_pedido` enum('em andamento','entregue') DEFAULT 'em andamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_tinta`, `usuario_email`, `finalidade`, `status`, `data_pedido`, `id_usuario`, `quantidade_solicitada`, `dia_retirada`, `horario_retirada`, `local_retirada`, `status_pedido`) VALUES
(2, 32, 'Gustavo@gmail.com', '', '', '2024-11-10 20:07:41', 10, 121, NULL, NULL, NULL, 'em andamento'),
(3, 30, 'Gustavo@gmail.com', '', '', '2024-11-17 00:44:51', 0, 10, 'Segunda-feira', '08:00 às 11:00', 'Fatec Jundiaí', 'em andamento'),
(4, 38, 'Gustavo@gmail.com', '', '', '2024-11-17 00:57:55', 0, 10, 'Segunda-feira', '08:00 às 11:00', 'Fatec Jundiaí', 'em andamento'),
(5, 38, 'Gustavo@gmail.com', '', '', '2024-11-17 01:16:47', 0, 10, 'Segunda-feira', '08:00 às 11:00', 'Fatec Jundiaí', 'em andamento');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tintas`
--

CREATE TABLE `tintas` (
  `usuario_email` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `quantidade_litros` float NOT NULL,
  `validade` date NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `tipo_embalagem` varchar(20) NOT NULL,
  `local_doacao` varchar(255) DEFAULT NULL,
  `dia_doacao` varchar(10) DEFAULT NULL,
  `horario_doacao` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT '0',
  `indicacao_aplicacao` varchar(50) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo_linha` varchar(20) NOT NULL,
  `acabamento` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tintas`
--

INSERT INTO `tintas` (`usuario_email`, `id`, `cor`, `quantidade_litros`, `validade`, `imagem`, `tipo_embalagem`, `local_doacao`, `dia_doacao`, `horario_doacao`, `status`, `indicacao_aplicacao`, `marca`, `usuario_id`, `tipo_linha`, `acabamento`) VALUES
('', 30, 'azul bebe', 500000, '0000-00-00', 'fotooo.webp', '', '', '', '', 'confirmada', '', '', NULL, '', ''),
('', 32, 'Azul bebe', 6000, '2024-11-30', 'fotooo.webp', '', 'Fatec Jundiaí', 'Segunda-fe', '13:00 às 17:00', '', 'Alvenaria', '', NULL, 'Premium', 'Acetinado'),
('', 34, 'amarela', 10, '2025-01-10', 'fotooo.webp', '', 'Fatec Jundiaí', 'Segunda-fe', '13:00 às 17:00', '', 'Alvenaria', '', NULL, 'Premium', 'Acetinado'),
('', 37, 'Preta', 23, '2024-11-30', 'fotooo.webp', '', 'Outros postos de coleta', 'Segunda-fe', '08:00 às 11:00', '', 'Alvenaria', '', NULL, 'Premium', 'Fosco'),
('', 38, 'Amarela', 200, '0000-00-00', 'fotooo.webp', '', '', '', '', 'confirmada', '', '', NULL, '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `endereco` varchar(150) NOT NULL,
  `idade` int(10) NOT NULL,
  `genero` tinyint(4) DEFAULT NULL,
  `senha` varchar(32) NOT NULL,
  `nivelacesso` tinyint(4) NOT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `numero` varchar(15) DEFAULT NULL,
  `admin_codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`codigo`, `nome`, `endereco`, `idade`, `genero`, `senha`, `nivelacesso`, `cep`, `logradouro`, `bairro`, `cidade`, `estado`, `email`, `numero`, `admin_codigo`) VALUES
(9, 'Admin', 'Fatec JD', 21, 1, '0000', 2, '13201-16', NULL, NULL, 'Jundiaí', NULL, 'admin@root.com', '18', NULL),
(10, 'gustavo', 'Rua israel', 21, 1, '21', 1, '07942280', NULL, NULL, 'Francisco Morato', NULL, 'Gustavo@gmail.com', '334', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tinta_id` (`id_tinta`),
  ADD KEY `usuario_email` (`usuario_email`);

--
-- Índices de tabela `tintas`
--
ALTER TABLE `tintas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tintas`
--
ALTER TABLE `tintas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_tinta`) REFERENCES `tintas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`usuario_email`) REFERENCES `usuario` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
