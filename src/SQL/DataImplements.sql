INSERT INTO `campus` (`id`, `nom`) VALUES
(1, 'Nantes'),
(2, 'Niort'),
(3, 'Renne');

INSERT INTO `villes` (`id`, `nom`,`code_postal`) VALUES
(1, 'Nantes',44000),
(2, 'Niort',79000),
(3, 'Rennes',35000);

/* Admin mdp Pa$$w0rd */
INSERT INTO `participant` (`id`, `nom`, `prenom`, `telephone`, `password`, `actif`, `email`, `roles`, `campus_id`) VALUES
(1, 'Application', 'Admin', '0000000000', '$2y$13$jdW0fksE6eIsPm1GxK6BxeK/KPKVGosXgnY.w1701rbNfuegVH2Py', NULL, 'admin@sortir.fr', '[\"ROLE_ADMIN\"]', 1);

INSERT INTO `etats` (`id`, `libelle`) VALUES
(1, 'Créée'),
(2, 'Ouverte'),
(3, 'Clôturée'),
(4, 'Activité en cours'),
(5, 'Passée'),
(6, 'Annulée');