-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bibliotecapersonal
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `libros`
--

DROP TABLE IF EXISTS `libros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libros` (
  `id_libro` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `fecha_lectura` date NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `genero` varchar(100) NOT NULL,
  PRIMARY KEY (`id_libro`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libros`
--

LOCK TABLES `libros` WRITE;
/*!40000 ALTER TABLE `libros` DISABLE KEYS */;
INSERT INTO `libros` VALUES (16,'Alas de Sangre','Rebeca Yarros','2025-01-07',1,'imagenes/alassangre.png','Fantasía'),(17,'Alas de Hierro','Rebeca Yarros','2025-02-25',1,'imagenes/alasdehierropng.png','Fantasía'),(18,'Pétalos de Papel','Iria y Selene','2025-02-04',1,'imagenes/petalos papel.png','Fantasía, romance'),(19,'Indomita','Raisa Martín ','2025-02-28',1,'imagenes/INDOMITA.png','Fantasía, romance'),(20,'Asesino de Brujas','Shelby Mahurin','2025-03-03',1,'imagenes/asesinodebrujas.png','Fantasía, romance'),(21,'Asesino de Brujas 2','Shelby Mahurin','2025-03-05',1,'imagenes/asesinodebnrujas2png.png','Fantasía, romance'),(22,'Ser Feliz en Alaska','Rafael Santandreu','2024-12-03',8,'imagenes/serfelizea.png','Autoayuda'),(24,'Por si las voces vuelven','Ángel Martín','2024-11-12',8,'imagenes/porsilasvoces.png','Autobiografía'),(25,'Las gafas de la Felicidad','Rafael Santandreu','2024-11-13',8,'imagenes/lasgafasdelafelici.png','Autoayuda'),(26,'La canción de Aquiles','Madeline Miller','2025-02-18',8,'imagenes/aquiles.png','Novela bélica'),(27,'El familiar','Leigh Bardugo','2025-03-12',8,'imagenes/elfamiliar.png','Fantasía histórica');
/*!40000 ALTER TABLE `libros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resenias`
--

DROP TABLE IF EXISTS `resenias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resenias` (
  `id_resenia` int(11) NOT NULL AUTO_INCREMENT,
  `comentario` text NOT NULL,
  `calificacion` int(10) unsigned DEFAULT NULL COMMENT 'debe estar entre 1 y 5',
  `id_usuario` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  PRIMARY KEY (`id_resenia`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resenias`
--

LOCK TABLES `resenias` WRITE;
/*!40000 ALTER TABLE `resenias` DISABLE KEYS */;
INSERT INTO `resenias` VALUES (8,'Es una historia muy adictiva, con los personajes bien desarrollados y es posible apreciar a lo largo de la trama como evolucionan con respecto a los distintos hechos que van ocurriendo. Es un balance perfecto entre el romance, acción y fantasía. Y siempre van a haber frases que te hacen morir de la risa. Recomendadisimo 10/10.',5,7,8),(9,'Es una historia muy adictiva, con los personajes bien desarrollados y es posible apreciar a lo largo de la trama como evolucionan con respecto a los distintos hechos que van ocurriendo. Es un balance perfecto entre el romance, acción y fantasía. Y siempre van a haber frases que te hacen morir de la risa. Recomendadisimo 10/10.',5,7,9),(10,'Pétalos de papel es una obra cautivadora que combina fantasía, emociones profundas y una narrativa que te transporta a un mundo lleno de magia y secretos. Iria y Selene demuestran una vez más su habilidad para crear historias que emocionan y personajes que se quedan contigo mucho después de cerrar el libro.\r\nLa riqueza del mundo creado por las autoras y la profundidad de sus protagonistas hacen de Pétalos de papel una lectura imprescindible. Es ideal tanto para quienes ya son fans de Iria y Selene como para quienes buscan una historia que les haga soñar y reflexionar al mismo tiempo.',5,7,10),(11,'Los lectores destacan la narrativa fluida, los personajes fuertes y la personalidad de las protagonistas. La historia es cautivadora y adictiva, con momentos intensos que mantienen al lector expectante constantemente. Destacan el buen argumento, la trama bien construida y los detalles únicos que lo hacen maravilloso. En general, se considera una lectura ligera, entretenida y recomendable para los amantes de los vampiros.',5,7,11),(12,'Este libro es un inicio de trilogía muy entretenido. Conocemos a Lou, una joven bruja que se oculta en las calles de Cesarine, la capital del reino. Pero su mala suerte la lleva a manos de un cazador de brujas metomentodo y cabezón, a la par que apuesto.\r\nEste cruce de caminos les llevará a contraer matrimonio para disipar las habladurías. Este libro se centra es ese enemies to lovers, esa atracción lenta pero imparable. Y es lo que más me ha gustado, tengo que decirlo, sus tiras y aflojas, sus conversaciones.\r\nA la par se nos presenta un mundo lleno de miedo a lo diferente. Se persigue a todo aquel que posea algo de magia o que pertenezca a una raza diferente. Esta historia habla de injusticias, de genocidios.\r\nLa trama es trepidante y te acerca a un final con algún que otro plot twist.',4,7,12),(13,'Iba con muchas expectativas después del primer libro, pero ha resultado ser un libro aburrido, insulso, no he visto el amor que se tenían los personajes al principio, muchas hojas solo para una escena aburrida. Ha sido un poco una decepción.',1,7,13),(15,'Es una historia muy adictiva, con los personajes bien desarrollados y es posible apreciar a lo largo de la trama como evolucionan con respecto a los distintos hechos que van ocurriendo. Es un balance perfecto entre el romance, acción y fantasía. Y siempre van a haber frases que te hacen morir de la risa. Recomendadisimo 10/10.',5,23,15),(16,'Es una historia muy adictiva, con los personajes bien desarrollados. Además, es posible apreciar a lo largo de la trama como evolucionan con respecto a los distintos hechos que van ocurriendo. Es un balance perfecto entre el romance, acción y fantasía. Y siempre van a haber frases que te hacen morir de la risa. La verdad que un 10/10.',5,1,16),(17,'El primer libro \\\"Alas de sangre\\\" me lo termine de leer muy rápido, ya que la historia se desarrollaba de forma rápida, fluida y sin mucho preámbulo.\\r\\nSin embargo, esta segunda parte \\\"Alas de hierro\\\" la encontré demasiado densa en términos de que incluye mucha información que podría ser omitida, ya que se vuelve repetitiva y hace que la lectura sea lenta. Hay pequeños momentos que te hacen enganchar mucho en la historia, pero luego vienen muchas páginas en donde solo se basa en dar relleno y más relleno. Sinceramente esta segunda parte no ha gustando tanto y se me ha hecho algo pesada de leer.',3,1,17),(18,'Pétalos de papel es una obra cautivadora que combina fantasía, emociones profundas y una narrativa que te transporta a un mundo lleno de magia y secretos. Iria y Selene demuestran una vez más su habilidad para crear historias que emocionan y personajes que se quedan contigo mucho después de cerrar el libro.\\r\\nLa riqueza del mundo creado por las autoras y la profundidad de sus protagonistas hacen de Pétalos de papel una lectura imprescindible. Es ideal tanto para quienes ya son fans de Iria y Selene como para quienes buscan una historia que les haga soñar y reflexionar al mismo tiempo.',5,1,18),(19,'El libro me ha gustado mucho. al principio me pareció un poco lento pero poco a poco se vuelve emocionante y no paras de leer. Los protagonistas me han encantado. Ver cómo la relación entre Sierra y Viktor pasa del odio al amor y otra vez al oído ha sido genial.\\r\\nEl plot twist del final ha sido genial. No me esperaba esa revelación y hace que encajen muchas cosas de la historia. Es una historia muy intensa para los amantes del romance y los vampiros.',5,1,19),(20,'Este libro me ha volado la cabeza. No he podido soltarlo ni por un minuto, me lo he devorado. Me enamoré de todos los personajes, todos son interesantes y genuinos. Todos ocultan secretos y eso te hace querer seguir leyendo. La relación que se va forjando entre cada uno de ellos es espectacular. El sarcasmo de Lou es impecable, un atributo que siempre suma. El romance es perfecto. La trama es atrapante. Me hubiera gustado ver más de la misteriosa antagonista, creo que no tuvo el protagonismo que merecía. Quizás lo tenga en los próximos libros. No puedo esperar para leer la continuación de esta trilogía.',5,1,20),(21,'Después de quedar encantada con el primer libro, este me ha parecido aburrido e insulso. No se ve el romance del primer libro entre los protagonistas, siento que pese a que se supone que están juntos no se observa esa magia que siempre han tenido. Hay muchas páginas de relleno y diálogos que no dicen nada. El final no ha sido del todo malo, pero no dice nada bueno de este libro haber leído 512 páginas y que lo interesante esté en las 50 últimas. De momento no voy a leerme el tercero, lo dejaré para más adelante.',1,1,21),(22,'Santandreu plantea aquí sus teorías sobre la actitud frente a la felicidad. Viene a decir que cómo vivamos nuestra vida depende en gran parte de cómo la encaremos y no de las circunstancias que nos rodean. Interesante aunque con matices utópicos en determinados planteamientos.',4,8,22),(24,'Me parece estupendo que se de visibilidad a la salud mental. Está escrito de tal forma que si has visto algún monólogo o la trayectoria en tv de Ángel, su forma de expresarse te concuerda perfectamente y convierte un tema serio en algo que pueda tratarse con humor sin que pase nada. Veo que hay gente que como todo en esta vida no ha sabido valorar esa forma de contarlo, es válido que no guste, a mi desde luego me ha encantado. Habla de sus adicciones con total naturalidad y todo desde su punto de vista, es un libro muy fresco, fácil de leer, me gusta la gente que se atreve a tratar temas serios sin ese aire casposo de telediario',5,8,24),(25,'¡Espectacular! Me ha recordado todo lo que había aprendido a base de fuertes experiencias y muchas horas de reflexión en soledad en mi juventud y que, en mi vida adulta, había quedado relegado al fondo de mi sentir, como resultado de la vorágine de responsabilidades que asumimos en esta sociedad ajetreada y consumista.',5,8,25),(26,'Es una maravillosa novela escrita con primor y sensibilidad por Madeline Miller. Una historia impresionante contada desde la perspectiva de uno de sus dos principales protagonistas; una historia de amor intenso y profundo entre dos príncipes: Aquiles, hijo de un rey y una diosa, y Patroclo, príncipe desterrado de su tierra por un error y acogido en otro reino. Patroclo nos cuenta su historia desde niño y su exilio, en el que es acogido por Aquiles, que lo lleva con él a todas partes. Desde su entrenamiento cómo el gran guerrero que está destinado a ser hasta las puertas de Troya, donde librará su última batalla. Una perspectiva diferente del mito de la guerra de Troya y de su héroe más famoso, Aquiles; contada con exquisito lenguaje, sensibilidad y sentimiento. Una lectura que te atrapa enseguida, te remueve por dentro y no te deja indiferente. Una lectura muy recomendable.',5,8,26),(27,'Una buena historia con muchos “PEROS”\\r\\n\\r\\nLa trama es muy interesante PERO le falta intensidad.\\r\\nEl Romance es idóneo PERO le falta tensión.\\r\\nLos personajes están muy bien construidos PERO se les podría haber sacado mucho más partido, sobre todo a Santángel.\\r\\n\\r\\nNo hay duda de lo bien escrito que está y el maravilloso trabajo de documentación de la autora sobre el contexto histórico de la trama.\\r\\nSin embargo, me quedo con la sensación de que se queda corto en todos los sentidos.',3,8,27);
/*!40000 ALTER TABLE `resenias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Admin'),(2,'Usuario');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuarios_role` (`role_id`),
  CONSTRAINT `fk_usuarios_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Andrea','Maña Moreno','AndreaMM',1,'$2y$10$7RnmJSvSjT.G6CfbYF.It.hVn1jlH7FzwT0u1OLGb3XipnxqIIkWC'),(8,'Adrian','Jimenez Sumariva','AdrianJS',2,'$2y$10$8bheC2R4/L9ZqYb8TIUg0uYea0acPLB382hh9E2PJCWki9yT8G.oC'),(9,'Alba','Benítez Márquez','AlbaBM',2,'$2y$10$bn.j5ixqw41kTLRPvwSxSuP6M7asss6SKhb97MX9kfZVLIJ7Zotd6'),(10,'Luis','Acevedo Fuentes','LuisAF',2,'$2y$10$jX2OxBqgkZEyOrPcJJbX3.qTjteGaoWPpxwoyuli1W68r9Tm4wcJe'),(11,'Marta','Ruiz García','MartaRG',2,'$2y$10$z.a75jvCM9B5ppIUXnMlju.WgMXGGDI43My9c3MPLVxhDvuX1cOlO'),(12,'Sara','Matinez Rosario','SaraMR',2,'$2y$10$UhTZ9P4w2k5fSbd36UX.VuM8fqj/90O5Hjo18nciw64DkZjmK61ya'),(13,'Marcos','Ruiz Prats','MarcosRP',2,'$2y$10$jg1dnoUhHWqAcLRXmMP0Ruvx1nFSMt0EF3sCG8X47VTVUon8X2TN2'),(14,'Samuel','Pérez Aguilar','samuel_PA',2,'$2y$10$isnKWlP92j0LZHOZGaKReeZ4cIzoDpi04g6muI1nytkk1ThkJMCSO'),(17,'Any','Soler Macías','AnySM',2,'$2y$10$FcBmSSRUO/IWZ9mm4rjYHe9xmPpCQh4jKUTmzjOa/toVXgZmzBmy.');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-25 12:03:56
