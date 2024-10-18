-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2024 a las 13:48:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `enhancereads`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'David Goggins'),
(2, 'Dale Carnegie'),
(3, 'Sun Tzu'),
(4, 'Viktor Frankl'),
(5, 'Robert Kiyosaki'),
(6, 'Wim Hof'),
(7, 'Jordan Peterson'),
(8, 'James Clear'),
(9, 'Brian Tracy'),
(10, 'Napoleon Hill'),
(11, 'Alex Hormozi'),
(12, 'Allan Pease'),
(13, 'Cal Newport'),
(14, 'Matthew Dicks');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `genre` int(11) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `pages`, `year`, `cover`, `link`, `language`) VALUES
(1, 'No me puedes lastimar', 1, 2, 410, 2022, 'cover_book_1.webp', 'https://www.amazon.es/No-puedes-lastimar-desaf%C3%ADa-probabilidades/dp/1544529120', 'Español'),
(2, 'Cómo ganar amigos e influir sobre las personas', 2, 3, 318, 2008, 'cover_book_2.webp', 'https://www.amazon.es/ganar-amigos-influir-personas-Elipse/dp/8493664928', 'Español'),
(3, 'El arte de la guerra', 3, 5, 98, 2018, 'cover_book_3.webp', 'https://www.amazon.es/El-arte-guerra-Sun-Tzu/dp/8494709208', 'Español'),
(4, 'El hombre en busca del sentido', 4, 5, 168, 2021, 'cover_book_4.webp', 'https://www.amazon.es/El-hombre-en-busca-sentido/dp/8425432022', 'Español'),
(5, 'Padre Rico, padre Pobre', 5, 4, 384, 2023, 'cover_book_5.webp', 'https://www.amazon.es/Padre-Rico-padre-Pobre-ense%C3%B1an/dp/846633212X', 'Español'),
(6, 'Nunca terminar', 1, 2, 346, 2008, 'cover_book_6.webp', 'https://www.amazon.es/Nunca-terminar-Desencadena-guerra-interior/dp/1544534701', 'Español'),
(7, '¡Trágate ese sapo!', 9, 7, 72, 2012, 'cover_book_7.webp', 'https://www.amazon.es/%C2%A1Tr%C3%A1gate-estrategias-combatiendo-procrastinaci%C3%B3n-Singulares/dp/8441532451', 'Español'),
(8, '$100M Offers', 11, 4, 164, 2021, 'cover_book_8.webp', 'https://www.amazon.es/100M-Offers-People-Stupid-Saying/dp/173747574X/', 'Inglés'),
(9, 'Hábitos atómicos', 8, 7, 336, 2020, 'cover_book_9.webp', 'https://www.amazon.es/H%C3%A1bitos-at%C3%B3micos-Autoconocimiento-James-Clear/dp/8418118032', 'Español'),
(10, 'El método Wim Hof', 6, 2, 288, 2021, 'cover_book_10.webp', 'https://www.amazon.es/m%C3%A9todo-Wim-Hof-Trasciende-potencial/dp/8484458830', 'Español'),
(11, 'El Lenguaje Del Cuerpo', 12, 3, 412, 2010, 'cover_book_11.webp', 'https://www.amazon.es/El-lenguaje-del-cuerpo-interpretar/dp/8417208690/', 'Español'),
(12, 'Piense y hágase rico', 10, 4, 320, 2022, 'cover_book_12.webp', 'https://www.amazon.es/Piense-y-h%C3%A1gase-rico-EXITO/dp/8497778219', 'Español'),
(13, 'Deep Work', 13, 7, 295, 2016, 'cover_book_13.webp', 'https://www.amazon.es/Deep-Work-Focused-Success-Distracted/dp/0349411905/', 'Inglés'),
(14, 'Storyworthy', 14, 3, 304, 2018, 'cover_book_14.webp', 'https://www.amazon.es/Storyworthy-Engage-Persuade-through-Storytelling/dp/1608685489/', 'Inglés');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(1, 'General'),
(2, 'Biografia'),
(3, 'Social'),
(4, 'Finanzas'),
(5, 'Mentalidad'),
(6, 'Salud'),
(7, 'Productividad'),
(8, 'Deportes'),
(9, 'Estoicismo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `genre` int(11) NOT NULL,
  `language` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `edited_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id`, `user`, `title`, `content`, `genre`, `language`, `created_at`) VALUES
(1, 1, '✅ Introducción a Enhancereads ✅', 'Bienvenidos a todos, esta guía está enfocada en obtener el mayor potencial de Enhancereads y lograr todas las metas que te has propuesto.\r\n\r\n1️⃣ - 𝐕𝐞 𝐜𝐨𝐧 𝐜𝐚𝐥𝐦𝐚 😀: Es normal que te sientas un poco agobiado con querer ver todo el contenido lo más rápido posible para aplicarlo cuanto antes, pero... ¿realmente te sale rentable?\r\n\r\nLo más seguro es que NO, ya que mucha de la información acabarás olvidándola, y son justo los pequeños detalles los que marcan la diferencia.\r\n\r\nPor eso, es recomendable ir leyendo los posts y viendo los videos poco a poco, y estar CONCENTRADO para no perder detalles. Eso sí, sé constante y dedica tiempo todos los días a revisar la academia.\r\n\r\n2️⃣ - 𝐓𝐨𝐦𝐚 𝐧𝐨𝐭𝐚𝐬 📖: Esto es esencial, ya que la cantidad de información de ALTO VALOR que hay en la academia (y lo que vendrá) es gigantesca.\r\n\r\nOrganízate como quieras, pero te recomiendo usar un medio digital (como Notion o Word) en lugar de papel, porque así podrás buscar la información concreta de forma sencilla, agregar imágenes y vídeos, referencias a posts...\r\n\r\nAdemás, otra ventaja del medio digital es que podrás darle un toque único a tus apuntes y compartir los resúmenes con la comunidad.\r\n\r\n3️⃣- 𝐏𝐚𝐫𝐭𝐢𝐜𝐢𝐩𝐚 𝐞𝐧 𝐥𝐚 𝐜𝐨𝐦𝐮𝐧𝐢𝐝𝐚𝐝 🙋‍♂️: Esto también es clave si quieres exprimir al máximo la academia.\r\n\r\nEste punto implica CREAR POSTS de alto valor sobre temas interesantes e innovadores, COMENTAR en los posts de los demás, hacer nuevas amistades o crear ideas grupales, organizar quedadas...\r\n\r\n4️⃣ - 𝐀𝐩𝐥𝐢𝐜𝐚 𝐲 𝐞𝐧𝐬𝐞ñ𝐚 😎: Mucha gente se queda estancada y no progresa porque solo mira contenido y NO lo pone en práctica, ¡esto es un ERROR!\r\n\r\nUna vez aprendas algo nuevo, aplícalo en tu día a día, ya sea saludando a la gente para mejorar habilidades sociales, meditando 10 minutos para mejorar tu salud mental... Además, está demostrado que si enseñas a otras personas lo que has aprendido, reforzarás dicho conocimiento y más gente aprenderá.\r\n\r\nGracias por leer y ¡nos vemos! 🔱🔥', 1, 'Español', '2024-07-12 16:13:47'),
(2, 2, 'El minimalismo te hará más productivo', 'Antes de empezar en el desarrollo personal, estuve viendo cientos de videos sobre minimalismo, hice una limpieza profunda de mi habitación y documentos, e incluso me leí el libro \"La magia del orden\" de Marie Kondo (os lo recomiendo si estáis interesados).\r\n\r\nEl minimalismo no solo se basa en tener todo ordenado y en poseer pocos objetos materiales; es un enfoque de vida que también permite simplificar y reducir al mínimo las distracciones y las tareas para centrarse en lo esencial.\r\n\r\nAdemás, es un aspecto que apenas he visto tratado en el desarrollo personal, y pienso que es muy necesario combinarlo.\r\n\r\nOs comento algunos de los beneficios de aplicar el minimalismo en vuestra vida:\r\n\r\n1- Menos distracciones: Un espacio ordenado y limpio facilita la concentración en las tareas importantes, mejorando la eficiencia (por eso se recomienda tener un escritorio despejado antes de trabajar).\r\n\r\n2- Enfoque en lo esencial: El minimalismo permite identificar y priorizar lo que realmente importa en tu vida (objetos, amistades, tareas...). De esta forma, puedes dedicar más tiempo y energía a lo que realmente importa.\r\n\r\n3- Menos toma de decisiones: La toma de decisiones constante puede ser agotadora. Si simplificas las decisiones que tomas, desde la ropa que usas hasta las aplicaciones que tienes en el móvil, puedes conservar energía para decisiones más importantes.\r\n\r\n4 - Mayor claridad mental y menos estrés: Un estilo de vida minimalista ayuda a despejar la mente, pues la liberación de posesiones y compromisos innecesarios permite experimentar una sensación de calma y claridad mental.\r\n\r\n5 - Eficiencia en el uso del tiempo: Como todos sabemos, el tiempo es lo más valioso que existe... Al evitar actividades y compromisos que no aportan valor, puedes aprovechar mejor tu tiempo y dedicarlo a lograr tus objetivos.\r\n\r\nSi tenéis alguna duda, queréis el libro o necesitáis que os recomiende algún canal de YouTube, decídmelo.', 1, 'Español', '2024-07-28 16:07:35'),
(3, 3, '✔️GUÍA DEFINITIVA del ESTOICISMO🔱', 'Seguro que habéis escuchado sobre esta corriente en algún momento y que ser estoico te ayudará a desarrollarte como persona, pero ¿realmente sabes lo que es? Y, más importante, ¿sabes cómo aplicar esta corriente para mejorar tu vida?\r\n\r\nEs por ello que creo este post para vosotros, la comunidad de Enhancereads. Si llegas hasta el final, vas a poder tener una mentalidad estoica de verdad y querrás comerte el mundo.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n📈 𝐈𝐍𝐓𝐑𝐎𝐃𝐔𝐂𝐂𝐈𝐎́𝐍 / 𝐃𝐄𝐅𝐈𝐍𝐈𝐂𝐈𝐎́𝐍\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\nEl estoicismo es una escuela filosófica que tuvo su apogeo en la antigua Grecia y fue fundada por Zenón de Citio en el siglo III a.C.\r\n\r\nEsta corriente se centra en el desarrollo personal a través del control de las emociones, la aceptación de la realidad y la práctica de la virtud.\r\n\r\nDato extra: La palabra \"estoico\" deriva del pórtico (stoa) donde Zenón enseñaba, simbolizando la idea de que la sabiduría puede encontrarse en la serenidad frente a la adversidad.\r\n\r\n𝙏͟𝙀͟𝙉͟ 𝘾͟𝙇͟𝘼͟𝙍͟𝙊͟ 𝙀͟𝙎͟𝙏͟𝙊͟: 𝑳𝒐𝒔 𝒆𝒔𝒕𝒐𝒊𝒄𝒐𝒔 𝒔𝒆 𝒄𝒆𝒏𝒕𝒓𝒂𝒏 𝒆𝒏 𝒍𝒐 𝒒𝒖𝒆 𝒑𝒖𝒆𝒅𝒆𝒏 𝒄𝒐𝒏𝒕𝒓𝒐𝒍𝒂𝒓 𝒚 𝒂𝒄𝒆𝒑𝒕𝒂𝒏 𝒅𝒆 𝒇𝒐𝒓𝒎𝒂 𝒕𝒓𝒂𝒏𝒒𝒖𝒊𝒍𝒂 𝒍𝒐 𝒒𝒖𝒆 𝒏𝒐 𝒑𝒖𝒆𝒅𝒆𝒏 𝒄𝒂𝒎𝒃𝒊𝒂𝒓.\r\n\r\nEsto quiere decir que 𝐒𝐎𝐋𝐎 tienes que preocuparte por lo que 𝐓𝐔́ puedes hacer para cambiar, y no por los problemas de los demás.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n📝 𝐏𝐑𝐈𝐍𝐂𝐈𝐏𝐈𝐎𝐒 : En este punto se explican algunos de los principios del estoicismo\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\n1️⃣ 𝐕𝐢𝐫𝐭𝐮𝐝 (𝐀𝐫𝐞𝐭𝐞): Se busca la virtud y la excelencia a través de la sabiduría (tomar decisiones correctas), la justicia (tratar a todos de forma correcta), el coraje (ser valiente ante los desafíos) y la moderación (control del cuerpo y la mente).\r\n\r\n2️⃣ 𝐃𝐢𝐟𝐞𝐫𝐞𝐧𝐜𝐢𝐚𝐜𝐢𝐨́𝐧 𝐝𝐞 𝐥𝐚𝐬 𝐂𝐨𝐬𝐚𝐬:\r\n\r\nL͟͟͟o͟͟͟ q͟͟͟u͟͟͟e͟͟͟ p͟u͟͟͟e͟͟͟d͟͟͟e͟͟͟s͟͟͟ c͟o͟͟͟n͟͟͟t͟͟͟r͟͟͟o͟͟͟l͟͟͟a͟͟͟r͟͟͟: Los estoicos se centran en lo que está bajo su control directo (pensamientos, acciones y reacciones emocionales propias).\r\n\r\nL͟͟͟o͟͟͟ q͟͟͟u͟͟͟e͟͟͟ n͟o͟͟͟ p͟u͟͟͟e͟͟͟d͟͟͟e͟͟͟s͟͟͟ c͟o͟͟͟n͟͟͟t͟͟͟r͟͟͟o͟͟͟l͟͟͟a͟͟͟r͟͟͟: Aceptar con calma lo que no puedes cambiar (circunstancias externas, acciones de otras personas y eventos fuera de tu alcance) es parte crucial del estoicismo.\r\n\r\n3️⃣ 𝐀𝐜𝐞𝐩𝐭𝐚𝐜𝐢𝐨́𝐧 𝐝𝐞𝐥 𝐝𝐞𝐬𝐭𝐢𝐧𝐨 (𝐀𝐦𝐨𝐫 𝐅𝐚𝐭𝐢): Todo lo que sucede en la vida te puede enseñar algo. Es por ello que las derrotas tienes que verlas como oportunidades para crecer y mejorar y no como fracasos.\r\n\r\n4️⃣ 𝐌𝐨𝐦𝐞𝐧𝐭𝐨 𝐩𝐫𝐞𝐬𝐞𝐧𝐭𝐞 (𝐀𝐤𝐡𝐢𝐫𝐞𝐢𝐚): Para tener una vida plena, hay que enfocarse en el presente (vivir en el \"aquí y ahora\"). De nada sirve preocuparse por el pasado, pues no lo puedes cambiar.\r\n\r\n5️⃣𝐈𝐧𝐝𝐢𝐟𝐞𝐫𝐞𝐧𝐜𝐢𝐚 𝐡𝐚𝐜𝐢𝐚 𝐩𝐥𝐚𝐜𝐞𝐫𝐞𝐬 𝐬𝐮𝐩𝐞𝐫𝐟𝐥𝐮𝐨𝐬: Los estoicos buscan la felicidad y el significado de la vida en la virtud y el desarrollo personal en lugar de en los bienes materiales.\r\n\r\nDicho de otra forma, no se compran \"Lambos\" o ropa \"Gucci\" para aparentar.\r\n\r\n6️⃣ 𝐀𝐮𝐭𝐨𝐬𝐮𝐟𝐢𝐜𝐢𝐞𝐧𝐜𝐢𝐚: Hay que desarrollar una independencia emocional y reconocer que la verdadera felicidad proviene de uno mismo y no de los demás. Para este punto, meditar y practicar la gratitud de manera diaria es muy útil.\r\n\r\n7️⃣ 𝐃𝐞𝐬𝐚𝐩𝐞𝐠𝐨 𝐝𝐞 𝐥𝐨𝐬 𝐫𝐞𝐬𝐮𝐥𝐭𝐚𝐝𝐨𝐬: La mentalidad estoica implica dar nuestra mejor versión, pero al mismo tiempo, desapegarse de los resultados. Esto significa no obsesionarse con el éxito o el fracaso, sino centrarse en la calidad de nuestros esfuerzos.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n✅ 𝐁𝐄𝐍𝐄𝐅𝐈𝐂𝐈𝐎𝐒: Estas ventajas son espectaculares ¿Te las quieres perder? Yo se que NO...\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\n1️⃣ 𝐑𝐞𝐬𝐢𝐥𝐢𝐞𝐧𝐜𝐢𝐚 𝐞𝐦𝐨𝐜𝐢𝐨𝐧𝐚l: El estoicismo nos enseña a enfrentar los desafíos con serenidad y a desarrollar una resiliencia emocional, lo que permite manejar situaciones difíciles o complejas con mayor calma y control.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: Si pierdes el trabajo, vas a tratar la situación con serenidad, reflexionando sobre tus fortalezas, habilidades, y vas a desarrollar un plan de acción para encontrar un nuevo empleo.\r\n\r\n2️⃣ 𝐌𝐚𝐲𝐨𝐫 𝐜𝐥𝐚𝐫𝐢𝐝𝐚𝐝 𝐦𝐞𝐧𝐭𝐚𝐥: Al tener claro que solo hay que centrarse en lo que uno puede controlar, el estoicismo nos ayuda a reducir la ansiedad, mantener una mayor claridad mental y tomar decisiones más contrastadas y, por ende, más acertadas.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: En caso de tener problemas de dinero, te vas a centrar en elaborar un presupuesto, controlar gastos y buscar formas de ingresos adicionales en lugar de pedir préstamos y caer en deudas, que sería lo más fácil.\r\n\r\n3️⃣ 𝐃𝐞𝐬𝐚𝐩𝐞𝐠𝐨 𝐝𝐞 𝐫𝐞𝐬𝐮𝐥𝐭𝐚𝐝𝐨𝐬: Tener una mentalidad estoica te ayudará a desvincular tu felicidad de los resultados externos. De esta forma, vas a tener una actitud más equilibrada, positiva y centrada en tus metas y objetivos.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: Si tienes un objetivo ambicioso y no lo consigues (en mi caso, por ejemplo, obtener el número de una chica), te vas a centrar en el aprendizaje y crecimiento que has tenido durante el proceso, manteniendo una actitud positiva y centrada en seguir avanzando.\r\n\r\n4️⃣ 𝐅𝐨𝐦𝐞𝐧𝐭𝐨 𝐝𝐞 𝐥𝐚 𝐯𝐢𝐫𝐭𝐮𝐝: El estoicismo da mucha importancia a la hora de cultivar virtudes como la sabiduría, la justicia, la valentía y la moderación, lo cual lleva a tener una vida más ética y significativa.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: En lugar de actuar de manera injusta para obtener beneficios propios, vas a tomar decisiones éticas, incluso aunque conlleve dificultades o no te beneficie enteramente.\r\n\r\n5️⃣ 𝐀𝐩𝐫𝐞𝐜𝐢𝐚𝐜𝐢𝐨́𝐧 𝐝𝐞𝐥 𝐩𝐫𝐞𝐬𝐞𝐧𝐭𝐞: Practicar la aceptación y la gratitud de forma regular te ayudará a apreciar más el momento presente, en lugar de preocuparte constantemente por el pasado o el futuro.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: Si tienes una mala experiencia, vas a reflexionar sobre ello, lo vas a aceptar y vas a dejar que fluya. 𝐍𝐔𝐍𝐂𝐀 lo retengas porque te condicionará.\r\n\r\n6️⃣ 𝐌𝐞𝐣𝐨𝐫𝐚 𝐞𝐧 𝐥𝐚𝐬 𝐫𝐞𝐥𝐚𝐜𝐢𝐨𝐧𝐞𝐬 𝐢𝐧𝐭𝐞𝐫𝐩𝐞𝐫𝐬𝐨𝐧𝐚𝐥𝐞𝐬: Ser estoico puede mejorar tus relaciones sociales, pues te enseña a ser calmado, empático y a manejar los conflictos de manera más sosegada.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: En caso de conflicto, vas a saber cómo actuar para poner orden y vas a encontrar una solución eficaz al problema.\r\n\r\n--------------------------------------------------------------------------------------------------------------------------------\r\n🔱𝟓 𝐏𝐀𝐒𝐎𝐒 𝐏𝐀𝐑𝐀 𝐒𝐄𝐑 𝐄𝐒𝐓𝐎𝐂𝐈𝐎 𝐒𝐈𝐍 𝐅𝐑𝐀𝐂𝐀𝐒𝐀𝐑 𝐄𝐍 𝐄𝐋 𝐈𝐍𝐓𝐄𝐍𝐓𝐎 🔱\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\n1️⃣ 𝐄𝐬𝐭𝐮𝐝𝐢𝐚 𝐥𝐚 𝐟𝐢𝐥𝐨𝐬𝐨𝐟𝐢́𝐚 𝐞𝐬𝐭𝐨𝐢𝐜𝐚: Te recomiendo leer o escuchar historias de los principales filósofos estoicos (Epíteto, Marco Aurelio, Séneca...) para que puedas comprender mejor esta corriente.\r\n\r\n2️⃣ 𝐇𝐚𝐳 𝐮𝐧𝐚 𝐫𝐞𝐟𝐥𝐞𝐱𝐢𝐨́𝐧 𝐝𝐢𝐚𝐫𝐢𝐚: Dedica tiempo cada día a reflexionar sobre tus pensamientos, acciones y reacciones. Anótalos en un papel y de esta forma tu cerebro te lo agradecerá, hazme caso.\r\n\r\n3️⃣𝐏𝐫𝐞𝐠𝐮́𝐧𝐭𝐚𝐭𝐞 𝐚 𝐭𝐢 𝐦𝐢𝐬𝐦𝐨: Este diría que es el punto más importante de todos y consiste en reflexionar en si te estás enfocando en algo en lo que puedes controlar... Si no es el caso ya sabes que hacer, acéptalo de forma positiva, no de des vueltas y continua.\r\n\r\n4️⃣ 𝐃𝐞𝐬𝐚𝐫𝐫𝐨𝐥𝐥𝐚 𝐭𝐮𝐬 𝐯𝐢𝐫𝐭𝐮𝐝𝐞𝐬: Desarrolla y cultiva virtudes como la sabiduría, la valentía, la justicia y la moderación. Grábate a fuego que estas virtudes van a guiar tus decisiones y acciones.\r\n\r\n5️⃣ 𝐏𝐫𝐚𝐜𝐭𝐢𝐜𝐚 𝐞𝐥 𝐌𝐢𝐧𝐝𝐟𝐮𝐥𝐧𝐞𝐬𝐬 : Tienes que ser consciente del momento presente y reducir la influencia de pensamientos ansiosos sobre el pasado o el futuro. Para ello medita y expresa gratitud por las cosas positivas de tu vida.\r\n\r\nSeguro que tienes tiempos muertos en el día. No te pongas excusas, cumple con estos pasos y serás una persona totalmente diferente.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\nY hasta aquí la guía... Como siempre un placer contribuir es esta comunidad y cualquier duda/sugerencia escribirla en comentarios.\r\n\r\nSTAY HARD!\r\n', 9, 'Español', '2024-08-04 10:53:24'),
(4, 4, 'Cómo reducir y optimizar el uso de las pantallas ❌🖥️', 'Actualmente, pasamos muchas horas del día mirando pantallas, ya sean del móvil o del ordenador, y todos sabemos que esto es bastante negativo.\n\nEn mi caso, he logrado reducir mucho el uso del móvil en los últimos meses (mi media ahora está entre 20 y 30 minutos al día). Sin embargo, reducir el tiempo frente al ordenador es más complicado, ya que mi trabajo y estudios están relacionados con la informática.\n\nSi te pasa algo similar, te recomiendo utilizar algunas extensiones que te ayudarán a gestionar mejor tu tiempo frente a la pantalla. Yo utilizo Chrome, pero existen extensiones muy similares para otros navegadores.\n\n1️⃣ Web Activity Time Tracker: Esta extensión te permite controlar el tiempo que pasas en cada página durante el día. Además, puedes ver un histórico en semanas y meses representado en una gráfica circular. Esto te ayudará a ser más consciente de las horas que dedicas a cada sitio web y a tomar medidas si es necesario.\n\n2️⃣ Limit - Set Limits for Distracting Sites: Con esta extensión, puedes establecer un tiempo diario límite para cada página (ideal para redes sociales). Una vez alcanzado ese tiempo, se bloquea el acceso hasta el día siguiente. Es importante que, si decides usarla, no desactives el limitador; de lo contrario, no servirá de nada.\n\nEspero que estas herramientas os ayuden a reducir el tiempo que pasáis frente a la pantalla, y así podáis dedicar más tiempo a vuestras metas y objetivos 💪.\n\nSTAY HARD!', 6, 'Español', '2024-08-04 15:31:57'),
(5, 4, '🤚 Cuida tus uñas 🤚', 'Morderse las uñas es un hábito muy común, pero también bastante perjudicial, ya que puede generar problemas futuros, como que las uñas se claven en la piel.\r\n\r\nAlgunos de los motivos por los que se desarrolla este hábito son:\r\n\r\n1️⃣ Ansiedad y estrés: Morderse las uñas puede proporcionar un alivio temporal frente a la ansiedad y el estrés, aunque no resuelve el problema de fondo.\r\n\r\n2️⃣ Aburrimiento o falta de actividad: En momentos de inactividad, morderse las uñas puede convertirse en una forma de pasar el tiempo o liberar energía nerviosa.\r\n\r\nLos efectos negativos de este hábito son considerables, y si queremos vernos atractivos, es esencial cuidar nuestras uñas. No querrás dar un apretón de manos en una reunión formal y que noten el desastre que tienes...\r\n\r\nPor eso, aquí os dejo 5 beneficios de mantener unas uñas cuidadas:\r\n\r\n1️⃣ Protección: Las uñas protegen las yemas de los dedos, evitando lesiones y reduciendo la sensibilidad en estas.\r\n\r\n2️⃣ Higiene: Unas uñas bien cuidadas previenen la acumulación de gérmenes y reducen el riesgo de infecciones.\r\n\r\n3️⃣ Estética y confianza: Tener las uñas bien arregladas te hace ver más atractivo y, como resultado, aumenta tu autoestima. Además, demuestra que te fijas en los detalles.\r\n\r\n4️⃣ Indicadores de salud: Las uñas pueden reflejar problemas de salud. Por ejemplo, al apretarlas, se vuelven más blancas, lo que puede indicar ciertas condiciones de circulación.\r\n\r\n5️⃣ Prevención de enfermedades: Mantener las uñas cortas y limpias reduce el riesgo de propagar enfermedades y mejora la salud general de las manos.\r\n\r\nPersonalmente, utilizo un cortaúñas una vez a la semana y las limo para dejarlas sin picos. Si queréis haceros una manicura, no hay problema; eso depende de cada uno.\r\n\r\n¡Nos vemos!', 6, 'Español', '2024-08-04 21:32:54'),
(6, 6, '✅🛏️Haz la cama al despertarte', 'Esta pequeña tarea, que a priori parece insignificante, puede aportarnos grandes beneficios en nuestro día a día.\r\n\r\nAsí lo demuestra William H. McRaven en su libro \"Make Your Bed: Little Things That Can Change Your Life... and Maybe the World\".\r\n\r\nAlgunos de los beneficios son:\r\n\r\n1️⃣ Sensación de logro: Completar esta acción, que no lleva más de 5 minutos, te dará una sensación de victoria (la primera de muchas) para que tengas un día más productivo.\r\n\r\n2️⃣ Mejora la apariencia de tu habitación: Hacer la cama da una sensación de orden y genera un ambiente más agradable en tu espacio personal.\r\n\r\n3️⃣ Aprecio por tu espacio de descanso: Este simple gesto te ayuda a valorar y cuidar el lugar en el que descansas cada noche. Recuerda que pasas aproximadamente un tercio de tu vida en ese pequeño espacio.\r\n\r\n4️⃣ Bienestar mental y reducción del estrés: Un espacio ordenado y reconfortante al final del día contribuye a tu bienestar mental y reduce el estrés.\r\n\r\n5️⃣ Higiene y salud: Hacer la cama favorece la ventilación de las sábanas, lo que reduce la humedad y evita la aparición de ácaros y alérgenos.\r\n\r\nNo hay nada mejor que llegar a casa después de un día super productivo y encontrarte la cama preparada.', 5, 'Español', '2024-08-31 17:34:51'),
(7, 2, '🚿 Beneficios de las duchas frías 🥶', 'Seguro que habéis leído o escuchado en vídeos de desarrollo personal sobre los beneficios de las duchas frías, y hoy os voy a comentar las razones para considerarlas:\r\n\r\nAntes de nada, es importante aclarar que esto puede no ser la mejor opción para todos, especialmente para personas con problemas de salud.\r\n\r\n1️⃣ Mejora la circulación sanguínea: El agua fría estimula la circulación al contraer los vasos sanguíneos, lo que mejora la eficiencia del sistema circulatorio.\r\n\r\n2️⃣ Aumenta la energía y te despierta: Las duchas frías activan el sistema nervioso simpático, incrementando la energía y el estado de alerta. Pueden incluso sustituir un café o una bebida energética.\r\n\r\n3️⃣ Mejora la recuperación muscular: La exposición al frío reduce la inflamación y la hinchazón, lo cual es beneficioso para la recuperación muscular tras el ejercicio. No hace falta llegar al extremo de la crioterapia como Cristiano Ronaldo, pero yo he notado menos agujetas desde que las hago.\r\n\r\n4️⃣ Fortalece el sistema inmunológico: Exponerse al frío puede estimular la producción de glóbulos blancos, lo que fortalece el sistema inmunológico. No significa que nunca te resfríes, pero sí que tendrás una mejor resistencia.\r\n\r\n5️⃣ Mejora la piel y el cabello: El agua fría cierra los poros de la piel y sella las cutículas del cabello, mejorando su apariencia y salud.\r\n\r\n6️⃣ Ayuda en la pérdida de peso: El frío activa la grasa parda, un tipo de grasa que quema calorías para generar calor, lo que podría contribuir a la pérdida de peso.\r\n\r\n7️⃣ Aumenta la resistencia al estrés: Las duchas frías son una forma de estrés controlado que ayuda a aumentar la resistencia al estrés en general. Al final, estás saliendo de tu zona de confort, lo que te ayuda a afrontar otros problemas con una mejor actitud.\r\n\r\nEn cuanto a la duración de la ducha fría, si estás empezando, puedes ir aumentando la intensidad del frío de manera controlada (comienza con agua templada) e intenta aguantar unos segundos. A medida que ganes resistencia, podrás mantenerte varios minutos bajo el agua fría. En mi caso, con 2 o 3 minutos es más que suficiente.\r\n\r\n¡Hasta la próxima!', 6, 'Español', '2024-08-31 21:12:49'),
(8, 5, 'Libro \"El arte de la guerra\" 📚', 'Recomiendo leer este gran libro, ya que enseña lecciones valiosas que siguen siendo aplicables hoy en día. Algunas de ellas son:\r\n\r\n1️⃣ Visión estratégica: El libro subraya la importancia de tener una visión estratégica al tomar decisiones, lo que nos permite enfrentar desafíos y alcanzar nuestras metas de manera efectiva.\r\n\r\n2️⃣ Conocimiento propio: Sun Tzu destaca la importancia de conocerse a uno mismo, identificando nuestras fortalezas y debilidades. Esto es clave para mejorar nuestras habilidades y rendimiento personal.\r\n\r\n3️⃣ Adaptación al cambio: Aprender a adaptarnos a circunstancias cambiantes nos hace más flexibles y nos ayuda a encontrar soluciones efectivas en diversas situaciones.\r\n\r\n4️⃣ Diplomacia y negociación: El libro aborda estrategias para manejar conflictos de manera pacífica, mejorando nuestras habilidades de comunicación y fortaleciendo nuestras relaciones.\r\n\r\n5️⃣ Liderazgo personal: Antes de liderar a otros, debemos aprender a liderarnos a nosotros mismos. Aplicar principios de liderazgo eficaces nos permite influir positivamente en nuestro entorno y alcanzar el éxito personal.\r\n\r\nPuedes encontrar el libro en la sección de libros de la web.\r\n\r\n¡Saludos!', 5, 'Español', '2024-09-01 07:30:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_at` DATETIME DEFAULT NULL,
  `parent_comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `post_comments`
--

INSERT INTO `post_comments` (`id`, `user_id`, `post_id`, `comment`, `created_at`, `parent_comment_id`) VALUES
(1, 2, 1, '¡Muchas gracias por los consejos! La verdad es que viene muy bien tener una guía inicial.\r\n\r\n¡Saludos!', '2024-09-02 10:05:35', NULL),
(2, 4, 1, 'Super útil esta introducción, ahora ya sé cómo puedo sacar el máximo provecho a esta sección!', '2024-09-02 10:11:09', NULL),
(3, 1, 1, 'Dentro de poco se anunciarán más proyectos... 🤫 Estad atentos!', '2024-09-02 10:12:56', 1),
(4, 6, 2, 'Vi hace poco un video sobre el tema y he despejado mucho la mente. El cambio se nota una barbaridad 😄', '2024-09-02 10:32:42', NULL),
(5, 2, 3, '¡Esta información vale oro! Sigue así.', '2024-09-02 10:35:16', NULL),
(6, 4, 4, 'Para el móvil, casi todos los teléfonos incorporan un límite de uso de las aplicaciones. Si por lo que sea no lo tenéis, mirad alguna aplicación en la tienda. Yo he reducido mi tiempo de uso de 4 horas de media a solo 30 minutos en un mes. 😎👍', '2024-09-02 10:39:04', NULL),
(7, 5, 4, 'Brutal Pilar, eres un claro ejemplo que con fuerza de voluntad puedes reducir el uso de las pantallas!', '2024-09-02 10:39:54', 6),
(8, 6, 3, '¡Super 🔝esta guía! Me la voy a guardar para leer en los momentos que sienta debilidad, pues seguro que podré sacar el lado positivo de cualquier inconveniente.\r\n\r\nPor ejemplo, el otro día estaba en la fila del supermercado esperando a pagar y un hombre dijo que me había colado. Obviamente, al ser mentira, se lo dije educadamente para evitar discusiones y malas vibras con los demás. En lugar de enfadarme, me centré en mantener la calma y el buen ambiente. ¡Una excelente forma de aplicar lo que aprendí!\r\n\r\nSaludos 👋', '2024-09-02 10:46:05', NULL),
(9, 2, 5, 'Totalmente de acuerdo, es algo que debí empezar hace tiempo... Ahora las tengo perfectas y sin ningún problema.', '2024-09-02 10:49:23', NULL),
(10, 2, 3, 'Hiciste bien amigo. \r\nNo te molestes por la actitud de los demás, es su problema el cómo reaccionar y afrontar la situación.', '2024-09-02 10:50:44', 8),
(11, 3, 3, 'Me alegra que todo quedara en una anécdota y que supiste mantener la mente fría. Cuídate', '2024-09-02 10:51:47', 8),
(12, 2, 6, 'Hacer la cama es algo que ya me sale de manera inconsciente; sin duda, la primera victoria del día.', '2024-09-02 10:55:19', NULL),
(13, 2, 8, 'Lo he leído hace poco y no me arrepiento en absoluto 👌. Sinceramente, sigue siendo una obra maestra a pesar de ser tan antiguo.', '2024-09-02 10:58:51', NULL),
(14, 4, 8, 'Al leerlo me quedé con la frase \"Si no te conoces a ti mismo ni a tu oponente, en cada batalla serás derrotado\". Desde entonces, he cambiado la mentalidad y ahora me siento mejor.', '2024-09-02 11:01:24', NULL),
(15, 6, 8, 'No lo conocía, pero viendo el post y los comentarios, lo voy a comprar ya de ya. ¡Muchas gracias a todos por las opiniones!', '2024-09-02 11:03:05', NULL),
(16, 1, 7, 'Llevo medio año con ello y el siguiente paso son baños con hielo, estoy deseando hacerlo!😁', '2024-09-02 11:04:42', NULL),
(17, 4, 7, 'Leyendo a Wim Hof ya lo estaba pensando, pero este post me ha convencido para empezar. Os voy diciendo cómo me siento.', '2024-09-02 11:06:01', NULL),
(18, 2, 7, 'Esperamos tus posts con la evolución', '2024-09-02 11:06:56', 17),
(19, 1, 8, 'Brutal esa frase, me la guardo!', '2024-09-02 11:08:19', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `post_likes`
--

INSERT INTO `post_likes` (`id`, `user_id`, `post_id`, `created_at`, `comment_id`) VALUES
(1, 2, 1, '2024-09-02 10:05:45', NULL),
(2, 4, 1, '2024-09-02 10:08:33', NULL),
(3, 5, 1, '2024-09-02 10:19:31', NULL),
(4, 6, 1, '2024-09-02 10:12:47', NULL),
(5, 4, 1, '2024-09-02 10:22:34', 3),
(6, 2, 2, '2024-09-02 10:33:30', 4),
(7, 6, 2, '2024-09-02 10:33:50', NULL),
(8, 2, 3, '2024-09-02 10:34:27', NULL),
(9, 5, 4, '2024-09-02 10:40:23', 6),
(10, 2, 4, '2024-09-02 10:40:40', NULL),
(11, 4, 3, '2024-09-02 10:46:18', NULL),
(12, 2, 3, '2024-09-02 10:47:08', 8),
(13, 3, 3, '2024-09-02 10:47:08', 8),
(14, 6, 3, '2024-09-02 10:52:24', 11),
(15, 6, 3, '2024-09-02 10:52:26', 10),
(16, 1, 6, '2024-09-02 10:54:08', NULL),
(17, 6, 4, '2024-09-02 10:40:40', NULL),
(18, 2, 6, '2024-09-02 10:56:25', NULL),
(19, 5, 5, '2024-09-02 10:56:34', NULL),
(20, 5, 5, '2024-09-02 10:57:03', 9),
(21, 2, 8, '2024-09-02 10:58:55', NULL),
(22, 4, 8, '2024-09-02 11:01:28', NULL),
(23, 4, 8, '2024-09-02 11:01:29', 13),
(24, 4, 7, '2024-09-02 11:03:23', NULL),
(25, 1, 7, '2024-09-02 11:06:57', 17),
(26, 2, 7, '2024-09-02 11:06:57', 17),
(27, 4, 8, '2024-09-02 11:09:17', 19),
(28, 4, 8, '2024-09-02 11:09:23', 14),
(29, 1, 8, '2024-09-02 11:44:37', NULL),
(30, 1, 4, '2024-09-02 11:44:43', NULL),
(31, 1, 3, '2024-09-02 11:44:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(30) NOT NULL DEFAULT '0',
  `2fa_secret` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `user`, `email`, `password`, `avatar`, `role`, `2fa_secret`) VALUES
(1, 'ByMaNuLaTxR', 'admin@gmail.com', '$2y$12$0nGc1ZafVmlHmD756NcNAOhDpa7nbGVpHq3hmKz2s5giNcPQ2kJv6', 'avatar_user_1.webp', 'admin', 'ARCZFEVMEEZYZKIB'),
(2, 'Jorge', 'jorge@gmail.com', '$2y$12$8xnlxqi4KNpzhbVUyBzFA.bYtTH8N5K3Gfn2DneUCnFYoJLFmgzrW', 'avatar_user_2.webp', 'user', NULL),
(3, 'Manuel Luis', 'bymanulator@gmail.com', '', 'avatar_user_3.webp', 'google', NULL),
(4, 'Pilar Sousa', 'pilar12sousa@gmail.com', '$2y$12$CtGHI.tl444VBxdN0V1FHeANSfM15IaXFee2f9cr4eEJ1tgqrGa7G', 'default.svg', 'user', NULL),
(5, 'Manuel Luis Muñoz Sánchez', 'manuelluismunozsanchez@gmail.com', '', 'avatar_user_5.webp', 'google', NULL),
(6, 'Chief Alekai', 'chiefalekai@gmail.com', '$2y$12$CtGHI.tl444VBxdN0V1FHeANSfM15IaXFee2f9cr4eEJ1tgqrGa7G', 'default.svg', 'user', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author`),
  ADD KEY `fk_books_genre` (`genre`);

--
-- Indices de la tabla `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user`),
  ADD KEY `genre_id` (`genre`);

--
-- Indices de la tabla `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indices de la tabla `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `fk_books_genre` FOREIGN KEY (`genre`) REFERENCES `genres` (`id`);

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`genre`) REFERENCES `genres` (`id`);

--
-- Filtros para la tabla `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `post_comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Filtros para la tabla `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
