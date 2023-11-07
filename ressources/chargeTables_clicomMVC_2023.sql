DELETE from `commande`;
DELETE from client`;
DELETE from `utilisateur`;

INSERT INTO `client` VALUES 
	(1,'Monsieur','Tienun','Jean','112, rue du Départ',NULL,'13000','Marseille','0404040404'),
	(2,'Madame','Terrature','Julie','Résidence Mermoz','1234 Boulevard des Aviateurs','14000','Caen','0202020202'),
	(3,'Madame','Cerf','Paulette','343 Avenue Henri Barbusse',NULL,'33000','Bordeaux','0550505050'),
	(4,'Mademoiselle','Morizet','Patricia','Hameau de Pau','23 Boulevard du Lycée','33000','Bordeaux','0250505052'),
	(5,'Monsieur','Nolapin','Jean','12 quai des Brumes',NULL,'83000','Toulon','0404505050'),
	(6,'Monsieur','Etete','Martel','Résidence du Faron','30 rue du téléphérique','83000','Toulon','0250505050'),
	(7,'Monsieur','Entete','Martel','12 Avenue de Lille',NULL,'59140','Dunkerque','0250905057'),(8,'Madame','DUMANS','Henriette','Corniche des Bolides','Villa Ferrari','49000','Angers','0250765357');


INSERT INTO `commande` VALUES 
(98762,1,'2014-09-07',123454),(
98763,2,'2014-09-08',123455),
(98764,4,'2014-09-10',123487),
(98765,2,'2014-10-01',123475),
(98766,4,'2014-10-01',NULL),
(98767,5,'2014-10-01',123489),
(98768,6,'2014-10-01',NULL);


INSERT INTO `utilisateur` VALUES 
(1,'Dupont','$2y$10$vO1JzBB01SF9LMN89z.Hj.uLR.hWiwnKQfSie.KN.sQmDfm0am/Zy','2019-02-27 11:13:57'),
(2,'Tanguy','$2y$10$Dxe7zZRN1uaG423WVYzarutB6A08clI7O1rBnZI5CkZSs549qEaOK','2019-02-27 11:13:57');

