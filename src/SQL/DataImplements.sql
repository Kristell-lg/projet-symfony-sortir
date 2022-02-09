INSERT INTO `campus` (`id`, `nom`) VALUES
(1, 'Nantes'),
(2, 'Niort'),
(3, 'Renne');

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