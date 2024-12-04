-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2024 a las 15:41:04
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
  `author_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `isbn` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `books`
--

INSERT INTO `books` (`id`, `title`, `author_id`, `genre_id`, `publisher_id`, `pages`, `year`, `cover`, `link`, `language`, `description`, `isbn`) VALUES
(1, 'No me puedes lastimar', 1, 2, 1, 410, 2022, 'cover_book_1.webp', '[\"https://www.amazon.es/No-puedes-lastimar-desaf%C3%ADa-probabilidades/dp/1544529120\", \"https://www.casadellibro.com/libro-no-me-puedes-lastimar-domina-tu-mente-y-desafia-las-probabilidades/9781544529127/13101842\", \"https://www.fnac.es/livre-numerique/a9188251/No-me-puedes-lastimar\" , \"https://www.buscalibre.es/libro-no-me-puedes-lastimar-domina-tu-mente-y-desafia-las-probabilidades/9781544529127/p/54121371\"]', 'Español', 'La pobreza, los prejuicios y los malos tratos físicos colorearon sus días y atormentaron sus noches. Pero gracias a su autodisciplina, su fortaleza mental y trabajo duro, Goggins pasó de ser un joven con sobrepeso, deprimido y sin futuro, a convertirse en un icono de las Fuerzas Armadas estadounidenses y en uno de los mejores atletas de resistencia del mundo. Es el único hombre de la historia que ha completado un entrenamiento de élite como SEAL de la Marina, Ranger del Ejército y Controlador Aéreo Táctico de las Fuerzas Aéreas, y llegó a batir récords en numerosas pruebas de resistencia, lo que inspiró a la revista Outside a nombrarlo El Hombre (Real) en Mejor Forma de Estados Unidos.\n\nEn No Me Puedes Lastimar, comparte la asombrosa historia de su vida y revela que la mayoría de nosotros aprovechamos sólo el 40% de nuestras capacidades. Goggins llama a esto La Regla del 40%, y su historia ilumina un camino que cualquiera puede seguir para superar el dolor, vencer al miedo y alcanzar su máximo potencial.', '9781544529127'),
(2, 'Cómo ganar amigos e influir sobre las personas', 2, 3, 2, 318, 2008, 'cover_book_2.webp', '[\"https:\\/\\/www.amazon.es\\/ganar-amigos-influir-personas-Elipse\\/dp\\/8412299728\",\"https:\\/\\/www.casadellibro.com\\/libro-como-ganar-amigos-e-influir-en-las-personas-nueva-edicion\\/9788412299724\\/13554634\",\"https:\\/\\/www.fnac.es\\/a10006059\\/Dale-Carnegie-Como-Ganar-Amigos-E-Influir-Sobre-Las-Personas\",\"https:\\/\\/www.buscalibre.es\\/libro-como-ganar-amigos-e-influir-sobre-las-personas\\/9788412299724\\/p\\/54586153\"]', 'Español', '\"Cómo ganar amigos\", clásico absoluto de las relaciones humanas, es un compendio de principios y verdades que aún hoy no han sido superados. Cuando inició este proyecto, Dale Carnegie decidió aplicar en él su gran conocimiento de las personas, sus dotes de observación y su experiencia profesional, y el resultado fue un tratado de psicología cotidiana que no ha perdido ni un ápice de actualidad y que, en buena medida, es el origen del marketing moderno. Según Carnegie, el éxito económico depende un 15 % del conocimiento profesional y el 85 % de la capacidad para expresar ideas, asumir el liderazgo y despertar entusiasmo en los demás. A través de consejos prácticos y ejemplos tomados tanto de personajes conocidos como de gente corriente, Carnegie nos muestra las técnicas fundamentales para ser empáticos y gustar a los demás, comprender sus puntos de vista y saber convencer de nuestras propias ideas sin causar resentimiento. Cómo ganar amigos, con muchos millones de ejemplares vendidos en todo el mundo, ha tocado una fibra sensible en nosotros generación tras generación. Ahora, con esta revisión, más actualizada que nunca, y en la que se ha añadido un prólogo de su hija, Donna Dale Carnegie, podremos seguir avanzando en nuestras relaciones humanas, alcanzar el éxito en nuestras vidas personales y profesionales y comprendernos a nosotros mismos un poco más.', '9788412299724'),
(3, 'El arte de la guerra', 3, 5, 3, 98, 2018, 'cover_book_3.webp', '[\"https://www.amazon.es/El-arte-guerra-Sun-Tzu/dp/8494709208\", \"https://www.casadellibro.com/libro-el-arte-de-la-guerra/9788494709203/7925837\", \"https://www.fnac.es/a6260908/El-arte-de-la-guerra\", \"https://www.buscalibre.es/libro-el-arte-de-la-guerra/9788494709203/p/50841419\"]', 'Español', '«Si conoces al enemigo y a ti mismo, tu victoria será segura; si conoces el Cielo y conoces la Tierra, puedes lograr que tu victoria sea completa». El arte de la guerra es el más reconocido tratado de estrategia militar de todos los tiempos. A pesar de haber sido escrito, presumiblemente, en el siglo V a.C., las ideas estipuladas por Sun Tzu mantienen plena vigencia y actualidad, puesto que los trece capítulos que componen la presenta obra transmiten una sabiduría difícilmente superable. Las enseñanzas de este libro son aplicables a todos aquellos ámbitos de la vida en los que los conflictos y las contradicciones tienen un especial protagonismo, como la política, la economía, la filosofía, las leyes o la psicología, entre otros, lo que lo convierte en un magnífico compendio de conocimiento de la naturaleza humana.', '9788494709203'),
(4, 'El hombre en busca del sentido', 4, 5, 4, 168, 2021, 'cover_book_4.webp', '[\"https://www.amazon.es/El-hombre-en-busca-sentido/dp/8425432022\", \"https://www.casadellibro.com/libro-el-hombre-en-busca-de-sentido/9788425432026/2742385\", \"https://www.fnac.es/a1183928/Viktor-Frankl-El-Hombre-En-Busca-De-Sentido\", \"https://www.buscalibre.es/libro-el-hombre-en-busca-de-sentido/9788425432026/p/47056974\"]', 'Español', '======Nueva tradicción====== El hombre en busca de sentido es mucho más que el testimonio de un psiquiatra sobre los hechos y los acontecimientos vividos en un campo de concentración. Es una lección existencial capaz de interpelar al lector de cualquier cultura, edad y procedencia. Y es además un clásico traducido a medio centenar de idiomas. El hombre en busca de sentido es mucho más que el testimonio de un psiquiatra sobre los hechos y los acontecimientos vividos en un campo de concentración. Es una lección existencial capaz de interpelar al lector de cualquier cultura, edad y procedencia. Y es además un clásico traducido a medio centenar de idiomas. El hombre en busca de sentido es el estremecedor relato en el que Viktor Frankl nos narra su experiencia en los campos de concentración. Durante esos años sintió en su propio ser lo que significaba una existencia absolutamente desprovista de todo, salvo de la existencia misma. Él, que todo lo había perdido, que padeció hambre, frío y brutalidades, pudo reconocer que, pese a todo, la vida es digna de ser vivida y que la libertad interior y la dignidad humana son indestructibles. En su condición de psiquiatra y prisionero, Frankl reflexiona con palabras de sorprendente esperanza sobre la capacidad humana de trascender las dificultades y descubrir una verdad profunda que nos orienta y da sentido a nuestras vidas. La logoterapia, método psicoterapéutico creado por el propio Frankl, se centra en el sentido de la existencia y en la búsqueda de ese sentido por parte del hombre, que asume la responsabilidad ante sí mismo, ante los demás y ante la vida.', '9788425432026'),
(5, 'Padre Rico, padre Pobre', 5, 4, 5, 384, 2023, 'cover_book_5.webp', '[\"https://www.amazon.es/Padre-padre-Pobre-edici%C3%B3n-actualizada/dp/8466373004\", \"https://www.casadellibro.com/libro-padre-rico-padre-pobre-ed-actualizada/9788466373005/13580701\", \"https://www.fnac.es/a10047766/Robert-T-Kiyosaki-Padre-Rico-padre-Pobre-edicion-actualizada\", \"https://www.buscalibre.es/libro-padre-rico-padre-pobre-edicion-actualizada/9788466373005/p/54880671\"]', 'Español', 'Padre rico, padre pobre es el libro de finanzas personales nº 1 en todo el mundo, el manual de Robert T. Kiyosaki que enseña a las personas a hacerse millonarias.\n\nBestseller de The New York Times, del Wall Street Journal, de Business Week y de USA Today.\n\nPadre rico, padre pobre te ayudará a...\n... derribar el mito de que necesitas tener ingresos elevados para hacerte rico.\n... desafiar la creencia de que tu casa es una inversión.\n... demostrar a los padres por qué no deben confiar en el sistema escolar para que sus hijos aprendan a manejar el dinero.\n... definir de una vez y para siempre qué es una inversión, y qué es una obligación.\n... saber qué debes enseñar a tus hijos sobre el dinero para que tengan éxito financiero en el futuro.\n\nRobert T. Kiyosaki ha transformado radicalmente la forma en que millones de personas alrededor del mundo perciben el concepto del dinero. Con perspectivas que contradicen el conocimiento convencional, Robert, también conocido como el «maestro» millonario, se ha ganado una gran reputación por hablar claro, ser irreverente y tener valor. Es reconocido en todo el mundo como un defensor apasionado de la educación financiera.', '9788466373005'),
(6, 'Nunca terminar', 1, 2, 1, 346, 2008, 'cover_book_6.webp', '[\"https:\\/\\/www.amazon.es\\/Nunca-terminar-Desencadena-guerra-interior\\/dp\\/1544534701\",\"https:\\/\\/www.casadellibro.com\\/libro-nunca-terminar-desencadena-tu-mente-y-gana-la-guerra-interior\\/9781544534701\\/13561749\",\"https:\\/\\/www.fnac.es\\/livre-numerique\\/a10018335\\/Nunca-terminar\",\"https:\\/\\/www.buscalibre.es\\/libro-nunca-terminar-desencadena-tu-mente-y-gana-la-guerra-interior\\/9781544534701\\/p\\/54557120\"]', 'Español', 'Esto no es un libro de autoayuda. ¡Es una llamada de atención! No me puedes lastimar, el gran éxito autobiográfico de David Goggins, demostró cuánta capacidad sin explotar todos tenemos, pero fue apenas una introducción al poder de la mente. En Nunca terminar, Goggins te lleva al interior de su Laboratorio Mental, donde desarrolló la filosofía, psicología y estrategias que le permitieron descubrir que aquello que consideraba su límite era tan sólo el punto de partida y que la búsqueda por la grandeza no tiene fin. Los relatos y lecciones en estas honestas, reveladoras e inquebrantables memorias ofrecen al lector un modelo a seguir para ascender desde el fondo del barril hasta una estratósfera completamente nueva que antes parecía inalcanzable. Si sientes que has perdido el rumbo en la vida, o estás buscando maximizar tu potencial o drenar tu alma para romper tu llamado techo de cristal, este es el único libro que necesitarás.', '9781544534695'),
(7, '¡Trágate ese sapo!', 9, 7, 6, 72, 2012, 'cover_book_7.webp', '[\"https:\\/\\/www.amazon.es\\/%C2%A1Tr%C3%A1gate-estrategias-combatiendo-procrastinaci%C3%B3n-Singulares\\/dp\\/8441532451\",\"https:\\/\\/www.casadellibro.com\\/libro-tragate-ese-sapo-21-estrategias-para-triunfar-combatiendo-la-pr-ocrastinacion\\/9788441532458\\/2034832\",\"https:\\/\\/www.fnac.es\\/a825229\\/Brian-Tracy-Tragate-Ese-Sapo-21-Estrategias-Para-Triunfar-Combatiendo-La-Procrastinacion\",\"https:\\/\\/www.buscalibre.es\\/libro-tragate-ese-sapo-21-estrategias-para-triunfar-combatiendo-la-procrastinacion-singulares-anaya\\/9788441532458\\/p\\/18558483\"]', 'Español', '¿Eres un procrastinador? Pues no estás solo en este mundo. Vivimos una época difícil y, quizá por eso maravillosa. Aprender a organizar el tiempo es la clave para alcanzar el éxito. Sin embargo, muchas veces, nos frena la procrastinación, esa tendencia tan humana a demorar las tareas complejas por dificultad, pereza o desaliento, a aplazar los asuntos pendientes. Mark Twain dijo una vez: lo primero que debemos hacer cada mañana es Tragarnos el sapo, así enfrentaremos el día con más decisión y optimismo. Pues bien, el sapo se refiere a la tarea más dura, quizás la más importante, esa que siempre se retrasa sin saber cómo evitarlo. La gente de éxito lo sabe: No hay tiempo suficiente para hacerlo todo. Hay que aprender a priorizar para poder cumplir. Comience por las tareas más difíciles e importantes, y una vez vencidas, la satisfacción personal y profesional te aportará la energía que necesitas para completar el resto. ¡Tráguese el sapo! profundiza sobre la utilización y la optimización del tiempo a través de una serie imprescindible de estrategias, sencillas pero definitivas. Aprende a desarrollar hábitos para combatir la procrastinación y trabajar con mayor resolución y eficiencia. Este maravilloso cómic, más accesible, arrebata la esencia del exitoso libro de Brian Tracy traducido ya a 23 idiomas. Brian Tracy es una autoridad en el desarrollo del potencial humano y la efectividad personal. Regresa ahora en formato de cómic con toda su sabiduría.', '9788441532458'),
(8, '$100M Offers', 11, 4, 7, 164, 2021, 'cover_book_8.webp', '[\"https:\\/\\/www.amazon.es\\/100M-Offers-People-Stupid-Saying\\/dp\\/1737475758\",\"https:\\/\\/www.casadellibro.com\\/ebook-100m-offers-how-to-make-offers-so-good-people-feel-stupid-saying-no-ebook\\/9798223100270\\/14221213\",\"https:\\/\\/www.fnac.es\\/livre-numerique\\/a10309683\\/New-Edition-100M-Offers-How-To-Make-Offers-So-Good-People-Feel-Stupid-Saying-No\",\"https:\\/\\/www.buscalibre.es\\/libro-100m-offers-how-to-make-offers-so-good-people-feel-stupid-saying-no-libro-en-ingles\\/9781737475750\\/p\\/55922513\"]', 'Inglés', '** OVER 500,000 COPIES SOLD!! **Number 1 Amazon BEST SELLER for over two years!I took home more in a year than the CEOs of McDonalds, IKEA, Ford, Motorola, and Yahoo….combined….as a kid in my twenties….using the $100M Offer method. It works. And it will work for you.Not that long ago though, my business had gotten so bad that I literally couldnt even give my services away for free. At the end of each month, I would look at my bank account hoping to see progress (but there wasnt). I knew something had to change...but what?Over the next 48 months, I went from losing money to making $36 for every $1 spent. In that time period, we generated over $120,000,000 across four different industries: service, e-commerce, software, and brick & mortar.But, unlike everyone else, we didnt have great funnels, great ads, or a wealthy niche. In fact, we didnt even send emails until we had crossed $50M in sales(!). Instead, we were able to do this one thing really well….we created offers so good, people felt stupid saying no.Heres exactly what this book will show you how to do:\r\n\r\nHow To Charge a Lot More Than You Currently Are…a- The Tiny Market Big Money Process we use to laser focus on niche markets overflowing with cashb- The \"Unfair\" Pricing formula….how we 100xd our pricing (and got more people to say yes….for real)c- The Value Flip...so you never get price compared again (thats a promise)d- The Virtuous Cycle of Price...use it to outspend your competition (for good) while using your product to attract the best talent.\r\n\r\nHow To Make Your Product So Good, Prospects Find A Way To Pay For Ita- The Unbeatable Value Equation….to make what you sell worth more than your prospects have ever receivedb- The Delivery Cube….to make delivering your products and services cost less but provide morec- The Trim & Stack Hack….to maximize profit using the absolute best delivery methods. (This has never been shared publicly and was how we made $17M in profit on $28M in revenue in a year when I was 28 years old).\r\n\r\nHow To Enhance Your Offer So Much, Prospects Buy Without Hesitatinga- The Scarcity Stack….how to use the three different types of scarcity in every offer you make (without lying) to get people to buy the moment you askb- The \"Everyday\" Urgency Blueprint... to get prospects to buy RIGHT NOW, using everyday life to create real, ethical time pressurec- Unbeatable Bonuses...and watch your prospects hesitations melt away as they begin reading their credit cards to you before you even finish!d- God-mode Guarantees...so good they make anyone say yes (even people who would never normally consider buying). Ill show you how to stack and layer all 4 types of guarantees together. I even give you my 13 favorite guarantees word-for-word to swipe for yourself.e- Magic Naming Formula to get the absolute highest response rates and conversion rates from everything you do to get new clients and so much more…\r\n\r\nThe methods contained within this book are so simple, so instantaneous, and so effective, its as if they work by magic. If you implement even one tactic in this book, youll see the change in your prospects demeanor. And youll know the $100M Offer method worked when you start hearing \"What do I need to do to move forward?\" ...before you even ask for the sale.\r\n\r\nIf you want to get more prospects to reply to your ads for less advertising dollars and get them to say YES to breathtaking prices...then ADD TO CART, use its contents, and see for yourself.', '9781737475750'),
(9, 'Hábitos atómicos', 8, 7, 8, 336, 2020, 'cover_book_9.webp', '[\"https:\\/\\/www.amazon.es\\/H%C3%A1bitos-at%C3%B3micos-Autoconocimiento-James-Clear\\/dp\\/8418118032\",\"https:\\/\\/www.casadellibro.com\\/libro-habitos-atomicos\\/9788418118036\\/11364192\",\"https:\\/\\/www.fnac.es\\/a7259403\\/James-Clear-Habitos-atomicos\",\"https:\\/\\/www.buscalibre.es\\/libro-habitos-atomicos\\/9788418118036\\/p\\/52972455\"]', 'Español', 'Más de 4 millones de ejemplares vendidos en todo el mundo. Un libro fascinante que cambiará el modo en que vivimos nuestro día a día.\r\n«Sumamente práctico y útil.» MARK MANSON, autor de El sutil arte de que (casi todo) te importe una mierda\r\n\r\n«Paso a paso, cambiará tu rutina.» Financial Times\r\n\r\nA menudo pensamos que para cambiar de vida tenemos que pensar en hacer cambios grandes. Nada más lejos de la realidad. Según el reconocido experto en hábitos James Clear, el cambio real proviene del resultado de cientos de pequeñas decisiones: hacer dos flexiones al día, levantarse cinco minutos antes o hacer una corta llamada telefónica.\r\n\r\nClear llama a estas decisiones “hábitos atómicos”: tan pequeños como una partícula, pero tan poderosos como un tsunami. En este libro innovador nos revela exactamente cómo esos cambios minúsculos pueden crecer hasta llegar a cambiar nuestra carrera profesional, nuestras relaciones y todos los aspectos de nuestra vida.\r\n\r\n«De mis libros favoritos de todos los tiempos.» ARIANNA HUFFINGTON, fundadora de The Huffington Post\r\n\r\n«Un discurso que se apoya en bases científicas, manual de instrucciones para implantar cambios a nuestro favor.» IMA SANCHÍS, La Vanguardia\r\n\r\n«Te harás fan.» CECILIA MÚZQUIZ, directora de Cosmopolitan\r\n\r\n«Demuestra que cualquier meta está al alcance de la mano, siempre y cuando empecemos desde lo más simple.» ABC Bienestar\r\n\r\n«Profundiza en todos aquellos aspectos necesarios para llevar una vida ordenada, productiva y orientada a disfrutar de los procesos, no solo de los resultados.» Xataka', '9788418118036'),
(10, 'El método Wim Hof', 6, 2, 9, 288, 2021, 'cover_book_10.webp', '[\"https:\\/\\/www.amazon.es\\/m%C3%A9todo-Wim-Hof-Trasciende-potencial\\/dp\\/8484458830\",\"https:\\/\\/www.casadellibro.com\\/libro-el-metodo-wim-hof-trasciende-tus-limites-activa-todo-tu-potencial\\/9788484458838\\/12259975\",\"https:\\/\\/www.fnac.es\\/a8177150\\/Wim-Hof-El-metodo-Wim-Hof\",\"https:\\/\\/www.buscalibre.es\\/libro-el-metodo-wim-hof-trasciende-tus-limites-activa-todo-tu-potencial\\/9788484458838\\/p\\/53213054\"]', 'Español', 'Wim Hof nos transmite un mensaje poderoso: \"Todos podemos conseguir lo imposible, literalmente. En nuestras manos está superar las enfermedades, mejorar nuestra salud mental y rendimiento físico, y hasta controlar nuestra fisiología para superar cualquier situación de estrés\". Este pionero defensor del potencial humano presenta en El método Wim Hof una técnica que cualquier persona —joven o de edad avanzada, sana o enferma— podrá emplear para sanar sus dolencias y recargarse al máximo de fuerza,vitalidad y felicidad.\r\nEn El metodo Wim Hof el autor describe su metodo y aborda, entre otros temas:\r\n\r\n• La respiración: Prácticas singulares que cambian la química del organismo,infunden energía y enfocan la mente.• El frío: Una amplia gama de prácticas seguras y controladas para aplicar la exposición al frío con el fin de potenciar el sistema cardiovascular y activar la energía desaprovechada del organismo.• La actitud mental: Ejercicios que reactivan la fuerza de voluntad, la claridad interior, la conciencia sensorial y la alegría innata del milagro que es vivir.• La ciencia: Una serie de estudios científicos que constatan que los usuarios de este método han amplia', '9788484458838'),
(11, 'El Lenguaje Del Cuerpo', 12, 3, 10, 412, 2010, 'cover_book_11.webp', '[\"https:\\/\\/www.amazon.es\\/El-lenguaje-del-cuerpo-interpretar\\/dp\\/8417208690\",\"https:\\/\\/www.casadellibro.com\\/libro-el-lenguaje-del-cuerpo-3-ed-como-interpretar-a-los-demas-a-tr-aves-de-sus-gestos\\/9788417208691\\/9076167\",\"https:\\/\\/www.fnac.es\\/a6439400\\/Barbara-Pease-El-lenguaje-del-cuerpo\",\"https:\\/\\/www.buscalibre.es\\/libro-el-lenguaje-del-cuerpo\\/9788417208691\\/p\\/51378678\"]', 'Español', 'El lenguaje del cuerpo revela más de las personas que sus propias palabras. Nuestros gestos pueden mostrar que nos sentimos inseguros, incómodos, molestos, desconfiados, decididos, fuertes o complacientes. Tampoco nos causa la misma impresión alguien que nos ofrece un buen apretón de manos que quien nos la tiende con ligereza, o quien se apoltrona en un sillón en vez de sentarse en el borde.\r\nLa manera como cruzamos los brazos o las piernas, movemos las manos o fijamos la mirada, por ejemplo, dice mucho de nosotros, y ser conscientes de ello puede ayudarnos a causar una impresión positiva en los demás, averiguar si alguien nos miente, salir con éxito de una entrevista de trabajo o, incluso, seducir a otras personas, ya sea para persuadirles o para elegir la pareja correcta. Los gestos hablan, así que ¡sácales todo el partido!', '9788417208691'),
(12, 'Piense y hágase rico', 10, 4, 11, 320, 2012, 'cover_book_12.webp', '[\"https:\\/\\/www.amazon.es\\/Piense-y-h%C3%A1gase-rico-EXITO\\/dp\\/8497778219\",\"https:\\/\\/www.casadellibro.com\\/libro-piense-y-hagase-rico\\/9788497778213\\/1959133\",\"https:\\/\\/www.fnac.es\\/a704142\\/Napoleon-Hill-Piense-y-hagase-rico\",\"https:\\/\\/www.buscalibre.es\\/libro-piense-y-hagase-rico\\/9788497778213\\/p\\/4904679\"]', 'Español', 'Un clásico para alcanzar la riqueza Tras más de veinte años investigando científicamente a los hombres más ricos de su época, Napoleon Hill aprendió el secreto de la riqueza del famoso industrial y escritor Andrew Carnegie. Carnegie no sólo llegó a ser multimillonario sino que hizo millonarios a una multitud de personas a las que enseñó su sabiduría. Piense y hágase rico es una obra diseñada a partir de una experiencia para conseguir el triunfo económico y personal de la humanidad entera. Gracias a este libro, la riqueza y la realización personal están al alcance de todas aquellas personas que lo deseen. No dejes el éxito en manos de unos pocos y lucha por tu trozo de pastel.', '9788497778213'),
(13, 'Deep Work', 13, 7, 13, 320, 2016, 'cover_book_13.webp', '[\"https:\\/\\/www.amazon.es\\/C%C3%A9ntrate-Deep-Work-distracci%C3%B3n-PENINSULA\\/dp\\/8411000516\",\"https:\\/\\/www.casadellibro.com\\/libro-centrate-deep-work\\/9788411000512\\/12657283\",\"https:\\/\\/www.fnac.es\\/a8903581\\/CORREA-MARIA-MERCEDES-Centrate-Deep-Work\",\"https:\\/\\/www.buscalibre.es\\/libro-centrate-deep-work-las-cuatro-reglas-para-el-exito-en-la-era-de-la-distraccion-peninsula\\/9788411000512\\/p\\/53911849\"]', 'Español', 'El «Deep Work» es la capacidad de concentrarse sin distracciones en una tarea cognitivamente exigente. En un mundo altamente competitivo que además incentiva la hiperconexión y la multitarea, la atención se ha convertido en un activo extremadamente valioso.  A partir de cuatro reglas prácticas, Carl Newport demuestra que reforzar nuestra capacidad de concentración y saber alejarse de las distracciones tecnológicas son los primeros pasos para lograr la felicidad y el éxito profesional.', '9788411000512'),
(14, 'Storyworthy', 14, 3, 12, 368, 2018, 'cover_book_14.webp', '[\"https:\\/\\/www.amazon.es\\/Storyworthy-Engage-Persuade-through-Storytelling\\/dp\\/1608685489\",\"https:\\/\\/www.buscalibre.es\\/libro-storyworthy-engage-teach-persuade-and-change-your-life-through-the-power-of-storytelling\\/9781608685486\\/p\\/50567323\"]', 'Inglés', 'A five-time Moth GrandSLAM winner and bestselling novelist shows how to tell a great story -- and why doing so matters. Whether we realize it or not, we are always telling stories. On a first date or job interview, at a sales presentation or therapy appointment, with family or friends, we are constantly narrating events and interpreting emotions and actions. In this compelling book, storyteller extraordinaire Matthew Dicks presents wonderfully straightforward and engaging tips and techniques for constructing, telling, and polishing stories that will hold the attention of your audience (no matter how big or small). He shows that anyone can learn to be an appealing storyteller, that everyone has something \"storyworthy\" to express, and, perhaps most important, that the act of creating and telling a tale is a powerful way of understanding and enhancing your own life.', '9781608685486');

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
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `notifier_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `type` enum('like','comment','reply') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `notifier_id`, `recipient_id`, `post_id`, `comment_id`, `created_at`, `is_read`, `type`) VALUES
(1, 6, 1, 2, NULL, '2024-11-08 12:40:07', 0, 'like'),
(2, 5, 1, 2, NULL, '2024-11-08 12:40:12', 1, 'comment'),
(3, 4, 2, 2, NULL, '2024-11-08 14:16:20', 1, 'comment'),
(4, 4, 1, 1, 1, '2024-11-08 14:16:38', 1, 'reply'),
(5, 1, 5, 8, NULL, '2024-11-08 18:24:35', 0, 'comment'),
(6, 1, 5, 8, NULL, '2024-11-08 18:24:57', 0, 'like'),
(7, 1, 5, 4, 7, '2024-11-08 18:57:05', 0, 'like'),
(8, 1, 6, 3, 8, '2024-11-08 19:47:08', 0, 'like'),
(9, 1, 2, 6, 12, '2024-11-09 10:18:18', 0, 'reply'),
(10, 2, 5, 8, NULL, '2024-11-09 16:11:21', 0, 'like'),
(11, 2, 6, 6, NULL, '2024-11-09 16:18:10', 0, 'comment'),
(12, 2, 6, 6, NULL, '2024-11-09 16:19:57', 0, 'comment'),
(13, 2, 4, 5, NULL, '2024-11-09 16:37:11', 0, 'like'),
(14, 2, 1, 7, 16, '2024-11-09 17:24:39', 0, 'like'),
(15, 2, 1, 7, 16, '2024-11-09 17:29:58', 0, 'like'),
(16, 2, 1, 7, 16, '2024-11-09 17:30:00', 0, 'reply'),
(17, 2, 4, 5, NULL, '2024-11-09 19:32:10', 0, 'comment'),
(18, 2, 5, 8, NULL, '2024-11-09 19:32:27', 0, 'comment'),
(19, 3, 4, 4, NULL, '2024-11-10 19:45:38', 0, 'like'),
(20, 2, 5, 8, NULL, '2024-11-11 19:02:22', 0, 'like');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `genre_id` int(11) NOT NULL,
  `language` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `genre_id`, `language`, `created_at`, `edited_at`) VALUES
(1, 1, '✅ Introducción a Enhancereads ✅', 'Bienvenidos a todos, esta guía está enfocada en obtener el mayor potencial de Enhancereads y lograr todas las metas que te has propuesto.\r\n\r\n1️⃣ - 𝐕𝐞 𝐜𝐨𝐧 𝐜𝐚𝐥𝐦𝐚 😀: Es normal que te sientas un poco agobiado con querer ver todo el contenido lo más rápido posible para aplicarlo cuanto antes, pero... ¿realmente te sale rentable?\r\n\r\nLo más seguro es que NO, ya que mucha de la información acabarás olvidándola, y son justo los pequeños detalles los que marcan la diferencia.\r\n\r\nPor eso, es recomendable ir leyendo los posts y viendo los videos poco a poco, y estar CONCENTRADO para no perder detalles. Eso sí, sé constante y dedica tiempo todos los días a revisar la academia.\r\n\r\n2️⃣ - 𝐓𝐨𝐦𝐚 𝐧𝐨𝐭𝐚𝐬 📖: Esto es esencial, ya que la cantidad de información de ALTO VALOR que hay en la academia (y lo que vendrá) es gigantesca.\r\n\r\nOrganízate como quieras, pero te recomiendo usar un medio digital (como Notion o Word) en lugar de papel, porque así podrás buscar la información concreta de forma sencilla, agregar imágenes y vídeos, referencias a posts...\r\n\r\nAdemás, otra ventaja del medio digital es que podrás darle un toque único a tus apuntes y compartir los resúmenes con la comunidad.\r\n\r\n3️⃣- 𝐏𝐚𝐫𝐭𝐢𝐜𝐢𝐩𝐚 𝐞𝐧 𝐥𝐚 𝐜𝐨𝐦𝐮𝐧𝐢𝐝𝐚𝐝 🙋‍♂️: Esto también es clave si quieres exprimir al máximo la academia.\r\n\r\nEste punto implica CREAR POSTS de alto valor sobre temas interesantes e innovadores, COMENTAR en los posts de los demás, hacer nuevas amistades o crear ideas grupales, organizar quedadas...\r\n\r\n4️⃣ - 𝐀𝐩𝐥𝐢𝐜𝐚 𝐲 𝐞𝐧𝐬𝐞ñ𝐚 😎: Mucha gente se queda estancada y no progresa porque solo mira contenido y NO lo pone en práctica, ¡esto es un ERROR!\r\n\r\nUna vez aprendas algo nuevo, aplícalo en tu día a día, ya sea saludando a la gente para mejorar habilidades sociales, meditando 10 minutos para mejorar tu salud mental... Además, está demostrado que si enseñas a otras personas lo que has aprendido, reforzarás dicho conocimiento y más gente aprenderá.\r\n\r\nGracias por leer y ¡nos vemos! 🔱🔥', 1, 'Español', '2024-07-12 16:13:47', NULL),
(2, 2, 'El minimalismo te hará más productivo', 'Antes de empezar en el desarrollo personal, estuve viendo cientos de videos sobre minimalismo, hice una limpieza profunda de mi habitación y documentos, e incluso me leí el libro \"La magia del orden\" de Marie Kondo (os lo recomiendo si estáis interesados).\r\n\r\nEl minimalismo no solo se basa en tener todo ordenado y en poseer pocos objetos materiales; es un enfoque de vida que también permite simplificar y reducir al mínimo las distracciones y las tareas para centrarse en lo esencial.\r\n\r\nAdemás, es un aspecto que apenas he visto tratado en el desarrollo personal, y pienso que es muy necesario combinarlo.\r\n\r\nOs comento algunos de los beneficios de aplicar el minimalismo en vuestra vida:\r\n\r\n1- Menos distracciones: Un espacio ordenado y limpio facilita la concentración en las tareas importantes, mejorando la eficiencia (por eso se recomienda tener un escritorio despejado antes de trabajar).\r\n\r\n2- Enfoque en lo esencial: El minimalismo permite identificar y priorizar lo que realmente importa en tu vida (objetos, amistades, tareas...). De esta forma, puedes dedicar más tiempo y energía a lo que realmente importa.\r\n\r\n3- Menos toma de decisiones: La toma de decisiones constante puede ser agotadora. Si simplificas las decisiones que tomas, desde la ropa que usas hasta las aplicaciones que tienes en el móvil, puedes conservar energía para decisiones más importantes.\r\n\r\n4 - Mayor claridad mental y menos estrés: Un estilo de vida minimalista ayuda a despejar la mente, pues la liberación de posesiones y compromisos innecesarios permite experimentar una sensación de calma y claridad mental.\r\n\r\n5 - Eficiencia en el uso del tiempo: Como todos sabemos, el tiempo es lo más valioso que existe... Al evitar actividades y compromisos que no aportan valor, puedes aprovechar mejor tu tiempo y dedicarlo a lograr tus objetivos.\r\n\r\nSi tenéis alguna duda, queréis el libro o necesitáis que os recomiende algún canal de YouTube, decídmelo.', 1, 'Español', '2024-07-28 16:07:35', NULL),
(3, 3, '✔️GUÍA DEFINITIVA del ESTOICISMO🔱', 'Seguro que habéis escuchado sobre esta corriente en algún momento y que ser estoico te ayudará a desarrollarte como persona, pero ¿realmente sabes lo que es? Y, más importante, ¿sabes cómo aplicar esta corriente para mejorar tu vida?\r\n\r\nEs por ello que creo este post para vosotros, la comunidad de Enhancereads. Si llegas hasta el final, vas a poder tener una mentalidad estoica de verdad y querrás comerte el mundo.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n📈 𝐈𝐍𝐓𝐑𝐎𝐃𝐔𝐂𝐂𝐈𝐎́𝐍 / 𝐃𝐄𝐅𝐈𝐍𝐈𝐂𝐈𝐎́𝐍\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\nEl estoicismo es una escuela filosófica que tuvo su apogeo en la antigua Grecia y fue fundada por Zenón de Citio en el siglo III a.C.\r\n\r\nEsta corriente se centra en el desarrollo personal a través del control de las emociones, la aceptación de la realidad y la práctica de la virtud.\r\n\r\nDato extra: La palabra \"estoico\" deriva del pórtico (stoa) donde Zenón enseñaba, simbolizando la idea de que la sabiduría puede encontrarse en la serenidad frente a la adversidad.\r\n\r\n𝙏͟𝙀͟𝙉͟ 𝘾͟𝙇͟𝘼͟𝙍͟𝙊͟ 𝙀͟𝙎͟𝙏͟𝙊͟: 𝑳𝒐𝒔 𝒆𝒔𝒕𝒐𝒊𝒄𝒐𝒔 𝒔𝒆 𝒄𝒆𝒏𝒕𝒓𝒂𝒏 𝒆𝒏 𝒍𝒐 𝒒𝒖𝒆 𝒑𝒖𝒆𝒅𝒆𝒏 𝒄𝒐𝒏𝒕𝒓𝒐𝒍𝒂𝒓 𝒚 𝒂𝒄𝒆𝒑𝒕𝒂𝒏 𝒅𝒆 𝒇𝒐𝒓𝒎𝒂 𝒕𝒓𝒂𝒏𝒒𝒖𝒊𝒍𝒂 𝒍𝒐 𝒒𝒖𝒆 𝒏𝒐 𝒑𝒖𝒆𝒅𝒆𝒏 𝒄𝒂𝒎𝒃𝒊𝒂𝒓.\r\n\r\nEsto quiere decir que 𝐒𝐎𝐋𝐎 tienes que preocuparte por lo que 𝐓𝐔́ puedes hacer para cambiar, y no por los problemas de los demás.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n📝 𝐏𝐑𝐈𝐍𝐂𝐈𝐏𝐈𝐎𝐒 : En este punto se explican algunos de los principios del estoicismo\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\n1️⃣ 𝐕𝐢𝐫𝐭𝐮𝐝 (𝐀𝐫𝐞𝐭𝐞): Se busca la virtud y la excelencia a través de la sabiduría (tomar decisiones correctas), la justicia (tratar a todos de forma correcta), el coraje (ser valiente ante los desafíos) y la moderación (control del cuerpo y la mente).\r\n\r\n2️⃣ 𝐃𝐢𝐟𝐞𝐫𝐞𝐧𝐜𝐢𝐚𝐜𝐢𝐨́𝐧 𝐝𝐞 𝐥𝐚𝐬 𝐂𝐨𝐬𝐚𝐬:\r\n\r\nL͟͟͟o͟͟͟ q͟͟͟u͟͟͟e͟͟͟ p͟u͟͟͟e͟͟͟d͟͟͟e͟͟͟s͟͟͟ c͟o͟͟͟n͟͟͟t͟͟͟r͟͟͟o͟͟͟l͟͟͟a͟͟͟r͟͟͟: Los estoicos se centran en lo que está bajo su control directo (pensamientos, acciones y reacciones emocionales propias).\r\n\r\nL͟͟͟o͟͟͟ q͟͟͟u͟͟͟e͟͟͟ n͟o͟͟͟ p͟u͟͟͟e͟͟͟d͟͟͟e͟͟͟s͟͟͟ c͟o͟͟͟n͟͟͟t͟͟͟r͟͟͟o͟͟͟l͟͟͟a͟͟͟r͟͟͟: Aceptar con calma lo que no puedes cambiar (circunstancias externas, acciones de otras personas y eventos fuera de tu alcance) es parte crucial del estoicismo.\r\n\r\n3️⃣ 𝐀𝐜𝐞𝐩𝐭𝐚𝐜𝐢𝐨́𝐧 𝐝𝐞𝐥 𝐝𝐞𝐬𝐭𝐢𝐧𝐨 (𝐀𝐦𝐨𝐫 𝐅𝐚𝐭𝐢): Todo lo que sucede en la vida te puede enseñar algo. Es por ello que las derrotas tienes que verlas como oportunidades para crecer y mejorar y no como fracasos.\r\n\r\n4️⃣ 𝐌𝐨𝐦𝐞𝐧𝐭𝐨 𝐩𝐫𝐞𝐬𝐞𝐧𝐭𝐞 (𝐀𝐤𝐡𝐢𝐫𝐞𝐢𝐚): Para tener una vida plena, hay que enfocarse en el presente (vivir en el \"aquí y ahora\"). De nada sirve preocuparse por el pasado, pues no lo puedes cambiar.\r\n\r\n5️⃣𝐈𝐧𝐝𝐢𝐟𝐞𝐫𝐞𝐧𝐜𝐢𝐚 𝐡𝐚𝐜𝐢𝐚 𝐩𝐥𝐚𝐜𝐞𝐫𝐞𝐬 𝐬𝐮𝐩𝐞𝐫𝐟𝐥𝐮𝐨𝐬: Los estoicos buscan la felicidad y el significado de la vida en la virtud y el desarrollo personal en lugar de en los bienes materiales.\r\n\r\nDicho de otra forma, no se compran \"Lambos\" o ropa \"Gucci\" para aparentar.\r\n\r\n6️⃣ 𝐀𝐮𝐭𝐨𝐬𝐮𝐟𝐢𝐜𝐢𝐞𝐧𝐜𝐢𝐚: Hay que desarrollar una independencia emocional y reconocer que la verdadera felicidad proviene de uno mismo y no de los demás. Para este punto, meditar y practicar la gratitud de manera diaria es muy útil.\r\n\r\n7️⃣ 𝐃𝐞𝐬𝐚𝐩𝐞𝐠𝐨 𝐝𝐞 𝐥𝐨𝐬 𝐫𝐞𝐬𝐮𝐥𝐭𝐚𝐝𝐨𝐬: La mentalidad estoica implica dar nuestra mejor versión, pero al mismo tiempo, desapegarse de los resultados. Esto significa no obsesionarse con el éxito o el fracaso, sino centrarse en la calidad de nuestros esfuerzos.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n✅ 𝐁𝐄𝐍𝐄𝐅𝐈𝐂𝐈𝐎𝐒: Estas ventajas son espectaculares ¿Te las quieres perder? Yo se que NO...\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\n1️⃣ 𝐑𝐞𝐬𝐢𝐥𝐢𝐞𝐧𝐜𝐢𝐚 𝐞𝐦𝐨𝐜𝐢𝐨𝐧𝐚l: El estoicismo nos enseña a enfrentar los desafíos con serenidad y a desarrollar una resiliencia emocional, lo que permite manejar situaciones difíciles o complejas con mayor calma y control.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: Si pierdes el trabajo, vas a tratar la situación con serenidad, reflexionando sobre tus fortalezas, habilidades, y vas a desarrollar un plan de acción para encontrar un nuevo empleo.\r\n\r\n2️⃣ 𝐌𝐚𝐲𝐨𝐫 𝐜𝐥𝐚𝐫𝐢𝐝𝐚𝐝 𝐦𝐞𝐧𝐭𝐚𝐥: Al tener claro que solo hay que centrarse en lo que uno puede controlar, el estoicismo nos ayuda a reducir la ansiedad, mantener una mayor claridad mental y tomar decisiones más contrastadas y, por ende, más acertadas.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: En caso de tener problemas de dinero, te vas a centrar en elaborar un presupuesto, controlar gastos y buscar formas de ingresos adicionales en lugar de pedir préstamos y caer en deudas, que sería lo más fácil.\r\n\r\n3️⃣ 𝐃𝐞𝐬𝐚𝐩𝐞𝐠𝐨 𝐝𝐞 𝐫𝐞𝐬𝐮𝐥𝐭𝐚𝐝𝐨𝐬: Tener una mentalidad estoica te ayudará a desvincular tu felicidad de los resultados externos. De esta forma, vas a tener una actitud más equilibrada, positiva y centrada en tus metas y objetivos.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: Si tienes un objetivo ambicioso y no lo consigues (en mi caso, por ejemplo, obtener el número de una chica), te vas a centrar en el aprendizaje y crecimiento que has tenido durante el proceso, manteniendo una actitud positiva y centrada en seguir avanzando.\r\n\r\n4️⃣ 𝐅𝐨𝐦𝐞𝐧𝐭𝐨 𝐝𝐞 𝐥𝐚 𝐯𝐢𝐫𝐭𝐮𝐝: El estoicismo da mucha importancia a la hora de cultivar virtudes como la sabiduría, la justicia, la valentía y la moderación, lo cual lleva a tener una vida más ética y significativa.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: En lugar de actuar de manera injusta para obtener beneficios propios, vas a tomar decisiones éticas, incluso aunque conlleve dificultades o no te beneficie enteramente.\r\n\r\n5️⃣ 𝐀𝐩𝐫𝐞𝐜𝐢𝐚𝐜𝐢𝐨́𝐧 𝐝𝐞𝐥 𝐩𝐫𝐞𝐬𝐞𝐧𝐭𝐞: Practicar la aceptación y la gratitud de forma regular te ayudará a apreciar más el momento presente, en lugar de preocuparte constantemente por el pasado o el futuro.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: Si tienes una mala experiencia, vas a reflexionar sobre ello, lo vas a aceptar y vas a dejar que fluya. 𝐍𝐔𝐍𝐂𝐀 lo retengas porque te condicionará.\r\n\r\n6️⃣ 𝐌𝐞𝐣𝐨𝐫𝐚 𝐞𝐧 𝐥𝐚𝐬 𝐫𝐞𝐥𝐚𝐜𝐢𝐨𝐧𝐞𝐬 𝐢𝐧𝐭𝐞𝐫𝐩𝐞𝐫𝐬𝐨𝐧𝐚𝐥𝐞𝐬: Ser estoico puede mejorar tus relaciones sociales, pues te enseña a ser calmado, empático y a manejar los conflictos de manera más sosegada.\r\n\r\nE͟j͟e͟m͟p͟l͟o͟: En caso de conflicto, vas a saber cómo actuar para poner orden y vas a encontrar una solución eficaz al problema.\r\n\r\n--------------------------------------------------------------------------------------------------------------------------------\r\n🔱𝟓 𝐏𝐀𝐒𝐎𝐒 𝐏𝐀𝐑𝐀 𝐒𝐄𝐑 𝐄𝐒𝐓𝐎𝐂𝐈𝐎 𝐒𝐈𝐍 𝐅𝐑𝐀𝐂𝐀𝐒𝐀𝐑 𝐄𝐍 𝐄𝐋 𝐈𝐍𝐓𝐄𝐍𝐓𝐎 🔱\r\n---------------------------------------------------------------------------------------------------------------------------------\r\n\r\n1️⃣ 𝐄𝐬𝐭𝐮𝐝𝐢𝐚 𝐥𝐚 𝐟𝐢𝐥𝐨𝐬𝐨𝐟𝐢́𝐚 𝐞𝐬𝐭𝐨𝐢𝐜𝐚: Te recomiendo leer o escuchar historias de los principales filósofos estoicos (Epíteto, Marco Aurelio, Séneca...) para que puedas comprender mejor esta corriente.\r\n\r\n2️⃣ 𝐇𝐚𝐳 𝐮𝐧𝐚 𝐫𝐞𝐟𝐥𝐞𝐱𝐢𝐨́𝐧 𝐝𝐢𝐚𝐫𝐢𝐚: Dedica tiempo cada día a reflexionar sobre tus pensamientos, acciones y reacciones. Anótalos en un papel y de esta forma tu cerebro te lo agradecerá, hazme caso.\r\n\r\n3️⃣𝐏𝐫𝐞𝐠𝐮́𝐧𝐭𝐚𝐭𝐞 𝐚 𝐭𝐢 𝐦𝐢𝐬𝐦𝐨: Este diría que es el punto más importante de todos y consiste en reflexionar en si te estás enfocando en algo en lo que puedes controlar... Si no es el caso ya sabes que hacer, acéptalo de forma positiva, no de des vueltas y continua.\r\n\r\n4️⃣ 𝐃𝐞𝐬𝐚𝐫𝐫𝐨𝐥𝐥𝐚 𝐭𝐮𝐬 𝐯𝐢𝐫𝐭𝐮𝐝𝐞𝐬: Desarrolla y cultiva virtudes como la sabiduría, la valentía, la justicia y la moderación. Grábate a fuego que estas virtudes van a guiar tus decisiones y acciones.\r\n\r\n5️⃣ 𝐏𝐫𝐚𝐜𝐭𝐢𝐜𝐚 𝐞𝐥 𝐌𝐢𝐧𝐝𝐟𝐮𝐥𝐧𝐞𝐬𝐬 : Tienes que ser consciente del momento presente y reducir la influencia de pensamientos ansiosos sobre el pasado o el futuro. Para ello medita y expresa gratitud por las cosas positivas de tu vida.\r\n\r\nSeguro que tienes tiempos muertos en el día. No te pongas excusas, cumple con estos pasos y serás una persona totalmente diferente.\r\n\r\n---------------------------------------------------------------------------------------------------------------------------------\r\nY hasta aquí la guía... Como siempre un placer contribuir es esta comunidad y cualquier duda/sugerencia escribirla en comentarios.\r\n\r\nSTAY HARD!\r\n', 9, 'Español', '2024-08-04 10:53:24', NULL),
(4, 4, 'Cómo reducir y optimizar el uso de las pantallas ❌🖥️', 'Actualmente, pasamos muchas horas del día mirando pantallas, ya sean del móvil o del ordenador, y todos sabemos que esto es bastante negativo.\n\nEn mi caso, he logrado reducir mucho el uso del móvil en los últimos meses (mi media ahora está entre 20 y 30 minutos al día). Sin embargo, reducir el tiempo frente al ordenador es más complicado, ya que mi trabajo y estudios están relacionados con la informática.\n\nSi te pasa algo similar, te recomiendo utilizar algunas extensiones que te ayudarán a gestionar mejor tu tiempo frente a la pantalla. Yo utilizo Chrome, pero existen extensiones muy similares para otros navegadores.\n\n1️⃣ Web Activity Time Tracker: Esta extensión te permite controlar el tiempo que pasas en cada página durante el día. Además, puedes ver un histórico en semanas y meses representado en una gráfica circular. Esto te ayudará a ser más consciente de las horas que dedicas a cada sitio web y a tomar medidas si es necesario.\n\n2️⃣ Limit - Set Limits for Distracting Sites: Con esta extensión, puedes establecer un tiempo diario límite para cada página (ideal para redes sociales). Una vez alcanzado ese tiempo, se bloquea el acceso hasta el día siguiente. Es importante que, si decides usarla, no desactives el limitador; de lo contrario, no servirá de nada.\n\nEspero que estas herramientas os ayuden a reducir el tiempo que pasáis frente a la pantalla, y así podáis dedicar más tiempo a vuestras metas y objetivos 💪.\n\nSTAY HARD!', 2, 'Español', '2024-08-04 15:31:57', '2024-11-09 16:51:53'),
(5, 4, '🤚 Cuida tus uñas 🤚', 'Morderse las uñas es un hábito muy común, pero también bastante perjudicial, ya que puede generar problemas futuros, como que las uñas se claven en la piel.\r\n\r\nAlgunos de los motivos por los que se desarrolla este hábito son:\r\n\r\n1️⃣ Ansiedad y estrés: Morderse las uñas puede proporcionar un alivio temporal frente a la ansiedad y el estrés, aunque no resuelve el problema de fondo.\r\n\r\n2️⃣ Aburrimiento o falta de actividad: En momentos de inactividad, morderse las uñas puede convertirse en una forma de pasar el tiempo o liberar energía nerviosa.\r\n\r\nLos efectos negativos de este hábito son considerables, y si queremos vernos atractivos, es esencial cuidar nuestras uñas. No querrás dar un apretón de manos en una reunión formal y que noten el desastre que tienes...\r\n\r\nPor eso, aquí os dejo 5 beneficios de mantener unas uñas cuidadas:\r\n\r\n1️⃣ Protección: Las uñas protegen las yemas de los dedos, evitando lesiones y reduciendo la sensibilidad en estas.\r\n\r\n2️⃣ Higiene: Unas uñas bien cuidadas previenen la acumulación de gérmenes y reducen el riesgo de infecciones.\r\n\r\n3️⃣ Estética y confianza: Tener las uñas bien arregladas te hace ver más atractivo y, como resultado, aumenta tu autoestima. Además, demuestra que te fijas en los detalles.\r\n\r\n4️⃣ Indicadores de salud: Las uñas pueden reflejar problemas de salud. Por ejemplo, al apretarlas, se vuelven más blancas, lo que puede indicar ciertas condiciones de circulación.\r\n\r\n5️⃣ Prevención de enfermedades: Mantener las uñas cortas y limpias reduce el riesgo de propagar enfermedades y mejora la salud general de las manos.\r\n\r\nPersonalmente, utilizo un cortaúñas una vez a la semana y las limo para dejarlas sin picos. Si queréis haceros una manicura, no hay problema; eso depende de cada uno.\r\n\r\n¡Nos vemos!', 6, 'Español', '2024-08-04 21:32:54', NULL),
(6, 6, '✅🛏️Haz la cama al despertarte', 'Esta pequeña tarea, que a priori parece insignificante, puede aportarnos grandes beneficios en nuestro día a día.\r\n\r\nAsí lo demuestra William H. McRaven en su libro \"Make Your Bed: Little Things That Can Change Your Life... and Maybe the World\".\r\n\r\nAlgunos de los beneficios son:\r\n\r\n1️⃣ Sensación de logro: Completar esta acción, que no lleva más de 5 minutos, te dará una sensación de victoria (la primera de muchas) para que tengas un día más productivo.\r\n\r\n2️⃣ Mejora la apariencia de tu habitación: Hacer la cama da una sensación de orden y genera un ambiente más agradable en tu espacio personal.\r\n\r\n3️⃣ Aprecio por tu espacio de descanso: Este simple gesto te ayuda a valorar y cuidar el lugar en el que descansas cada noche. Recuerda que pasas aproximadamente un tercio de tu vida en ese pequeño espacio.\r\n\r\n4️⃣ Bienestar mental y reducción del estrés: Un espacio ordenado y reconfortante al final del día contribuye a tu bienestar mental y reduce el estrés.\r\n\r\n5️⃣ Higiene y salud: Hacer la cama favorece la ventilación de las sábanas, lo que reduce la humedad y evita la aparición de ácaros y alérgenos.\r\n\r\nNo hay nada mejor que llegar a casa después de un día super productivo y encontrarte la cama preparada.', 5, 'Español', '2024-08-31 17:34:51', NULL),
(7, 2, '🚿 Beneficios de las duchas frías 🥶', 'Seguro que habéis leído o escuchado en vídeos de desarrollo personal sobre los beneficios de las duchas frías, y hoy os voy a comentar las razones para considerarlas:\n\nAntes de nada, es importante aclarar que esto puede no ser la mejor opción para todos, especialmente para personas con problemas de salud.\n\n1️⃣ Mejora la circulación sanguínea: El agua fría estimula la circulación al contraer los vasos sanguíneos, lo que mejora la eficiencia del sistema circulatorio.\n\n2️⃣ Aumenta la energía y te despierta: Las duchas frías activan el sistema nervioso simpático, incrementando la energía y el estado de alerta. Pueden incluso sustituir un café o una bebida energética.\n\n3️⃣ Mejora la recuperación muscular: La exposición al frío reduce la inflamación y la hinchazón, lo cual es beneficioso para la recuperación muscular tras el ejercicio. No hace falta llegar al extremo de la crioterapia como Cristiano Ronaldo, pero yo he notado menos agujetas desde que las hago.\n\n4️⃣ Fortalece el sistema inmunológico: Exponerse al frío puede estimular la producción de glóbulos blancos, lo que fortalece el sistema inmunológico. No significa que nunca te resfríes, pero sí que tendrás una mejor resistencia.\n\n5️⃣ Mejora la piel y el cabello: El agua fría cierra los poros de la piel y sella las cutículas del cabello, mejorando su apariencia y salud.\n\n6️⃣ Ayuda en la pérdida de peso: El frío activa la grasa parda, un tipo de grasa que quema calorías para generar calor, lo que podría contribuir a la pérdida de peso.\n\n7️⃣ Aumenta la resistencia al estrés: Las duchas frías son una forma de estrés controlado que ayuda a aumentar la resistencia al estrés en general. Al final, estás saliendo de tu zona de confort, lo que te ayuda a afrontar otros problemas con una mejor actitud.\n\nEn cuanto a la duración de la ducha fría, si estás empezando, puedes ir aumentando la intensidad del frío de manera controlada (comienza con agua templada) e intenta aguantar unos segundos. A medida que ganes resistencia, podrás mantenerte varios minutos bajo el agua fría. En mi caso, con 2 o 3 minutos es más que suficiente.\n\n¡Hasta la próxima!\n', 6, 'Español', '2024-08-31 21:12:49', '2024-11-09 19:12:57'),
(8, 5, 'Libro \"El arte de la guerra\" 📚', 'Recomiendo leer este gran libro, ya que enseña lecciones valiosas que siguen siendo aplicables hoy en día. Algunas de ellas son:\r\n\r\n1️⃣ Visión estratégica: El libro subraya la importancia de tener una visión estratégica al tomar decisiones, lo que nos permite enfrentar desafíos y alcanzar nuestras metas de manera efectiva.\r\n\r\n2️⃣ Conocimiento propio: Sun Tzu destaca la importancia de conocerse a uno mismo, identificando nuestras fortalezas y debilidades. Esto es clave para mejorar nuestras habilidades y rendimiento personal.\r\n\r\n3️⃣ Adaptación al cambio: Aprender a adaptarnos a circunstancias cambiantes nos hace más flexibles y nos ayuda a encontrar soluciones efectivas en diversas situaciones.\r\n\r\n4️⃣ Diplomacia y negociación: El libro aborda estrategias para manejar conflictos de manera pacífica, mejorando nuestras habilidades de comunicación y fortaleciendo nuestras relaciones.\r\n\r\n5️⃣ Liderazgo personal: Antes de liderar a otros, debemos aprender a liderarnos a nosotros mismos. Aplicar principios de liderazgo eficaces nos permite influir positivamente en nuestro entorno y alcanzar el éxito personal.\r\n\r\nPuedes encontrar el libro en la sección de libros de la web.\r\n\r\n¡Saludos!', 5, 'Español', '2024-09-01 07:30:11', NULL);

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
  `edited_at` timestamp NULL,
  `parent_comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `post_comments`
--

INSERT INTO `post_comments` (`id`, `user_id`, `post_id`, `comment`, `created_at`, `edited_at`, `parent_comment_id`) VALUES
(1, 2, 1, '¡Muchas gracias por los consejos! La verdad es que viene muy bien tener una guía inicial.\r\n\r\n¡Saludos!', '2024-09-02 10:05:35', NULL, NULL),
(2, 4, 1, 'Super útil esta introducción, ahora ya sé cómo puedo sacar el máximo provecho a esta sección!', '2024-09-02 10:11:09', NULL, NULL),
(3, 1, 1, 'Dentro de poco se anunciarán más proyectos... 🤫 Estad atentos!', '2024-09-02 10:12:56', NULL, 1),
(4, 6, 2, 'Vi hace poco un video sobre el tema y he despejado mucho la mente. El cambio se nota una barbaridad 😄', '2024-09-02 10:32:42', NULL, NULL),
(5, 2, 3, '¡Esta información vale oro! Sigue así.', '2024-09-02 10:35:16', NULL, NULL),
(6, 4, 4, 'Para el móvil, casi todos los teléfonos incorporan un límite de uso de las aplicaciones. Si por lo que sea no lo tenéis, mirad alguna aplicación en la tienda. Yo he reducido mi tiempo de uso de 4 horas de media a solo 30 minutos en un mes. 😎👍', '2024-09-02 10:39:04', NULL, NULL),
(7, 5, 4, 'Brutal Pilar, eres un claro ejemplo que con fuerza de voluntad puedes reducir el uso de las pantallas!', '2024-09-02 10:39:54', NULL, 6),
(8, 6, 3, '¡Super 🔝esta guía! Me la voy a guardar para leer en los momentos que sienta debilidad, pues seguro que podré sacar el lado positivo de cualquier inconveniente.\r\n\r\nPor ejemplo, el otro día estaba en la fila del supermercado esperando a pagar y un hombre dijo que me había colado. Obviamente, al ser mentira, se lo dije educadamente para evitar discusiones y malas vibras con los demás. En lugar de enfadarme, me centré en mantener la calma y el buen ambiente. ¡Una excelente forma de aplicar lo que aprendí!\r\n\r\nSaludos 👋', '2024-09-02 10:46:05', NULL, NULL),
(9, 2, 5, 'Totalmente de acuerdo, es algo que debí empezar hace tiempo... Ahora las tengo perfectas y sin ningún problema.', '2024-09-02 10:49:23', NULL, NULL),
(10, 2, 3, 'Hiciste bien amigo. \r\nNo te molestes por la actitud de los demás, es su problema el cómo reaccionar y afrontar la situación.', '2024-09-02 10:50:44', NULL, 8),
(11, 3, 3, 'Me alegra que todo quedara en una anécdota y que supiste mantener la mente fría. Cuídate', '2024-09-02 10:51:47', NULL, 8),
(12, 2, 6, 'Hacer la cama es algo que ya me sale de manera inconsciente; sin duda, la primera victoria del día.', '2024-09-02 10:55:19', NULL, NULL),
(13, 2, 8, 'Lo he leído hace poco y no me arrepiento en absoluto 👌. Sinceramente, sigue siendo una obra maestra a pesar de ser tan antiguo.', '2024-09-02 10:58:51', NULL, NULL),
(14, 4, 8, 'Al leerlo me quedé con la frase \"Si no te conoces a ti mismo ni a tu oponente, en cada batalla serás derrotado\". Desde entonces, he cambiado la mentalidad y ahora me siento mejor.', '2024-09-02 11:01:24', NULL, NULL),
(15, 6, 8, 'No lo conocía, pero viendo el post y los comentarios, lo voy a comprar ya de ya. ¡Muchas gracias a todos por las opiniones!', '2024-09-02 11:03:05', NULL, NULL),
(16, 1, 7, 'Llevo medio año con ello y el siguiente paso son baños con hielo, estoy deseando hacerlo!😁', '2024-09-02 11:04:42', NULL, NULL),
(17, 4, 7, 'Leyendo a Wim Hof ya lo estaba pensando, pero este post me ha convencido para empezar. Os voy diciendo cómo me siento.', '2024-09-02 11:06:01', NULL, NULL),
(18, 2, 7, 'Esperamos tus posts con la evolución', '2024-09-02 11:06:56', '2024-11-09 20:24:59', 17),
(19, 1, 8, 'Brutal esa frase, me la guardo!', '2024-09-02 11:08:19', '2024-11-12 19:36:06', 14);

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
(22, 4, 8, '2024-09-02 11:01:28', NULL),
(23, 4, 8, '2024-09-02 11:01:29', 13),
(24, 4, 7, '2024-09-02 11:03:23', NULL),
(25, 1, 7, '2024-09-02 11:06:57', 17),
(26, 2, 7, '2024-09-02 11:06:57', 17),
(27, 4, 8, '2024-09-02 11:09:17', 19),
(28, 4, 8, '2024-09-02 11:09:23', 14),
(30, 1, 4, '2024-09-02 11:44:43', NULL),
(31, 1, 3, '2024-09-02 11:44:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publishers`
--

CREATE TABLE `publishers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publishers`
--

INSERT INTO `publishers` (`id`, `name`) VALUES
(1, 'Lioncrest Publishing'),
(2, 'Elipse'),
(3, 'Dojo Ediciones'),
(4, 'Herder Editorial'),
(5, 'Debolsillo'),
(6, ' Anaya Multimedia'),
(7, 'Acquisition'),
(8, 'Diana Editorial'),
(9, 'Gaia Ediciones'),
(10, 'Amat'),
(11, 'Obelisco'),
(12, 'New World Library'),
(13, 'Ediciones Península');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(30) NOT NULL DEFAULT '0',
  `2fa_secret` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `user`, `email`, `password`, `avatar`, `role`, `2fa_secret`) VALUES
(1, 'Manuel', 'admin@enhancereads.com', '$2y$12$JP/CE.2Y3zq./8umGAF3uOXoGDB3EnS6ZPqjJgc2fe3vA0kXxUTHy', 'avatar_user_1.webp', 'admin', 'ARCZFEVMEEZYZKIB'),
(2, 'Jorge', 'jorge@gmail.com', '$2y$12$IeQxS4MjLSENSGarLgqBFu9y5mYtxX2Y1lo5.1ptPSAfhJO2bcP2y', 'avatar_user_2.webp', 'user', NULL),
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
  ADD KEY `author_id` (`author_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indices de la tabla `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifier_id` (`notifier_id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `genre_id` (`genre_id`);

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
-- Indices de la tabla `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- AUTO_INCREMENT de la tabla `publishers`
--
ALTER TABLE `publishers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`),
  ADD CONSTRAINT `books_ibfk_3` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`);

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`notifier_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_4` FOREIGN KEY (`comment_id`) REFERENCES `post_comments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Filtros para la tabla `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `post_comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `post_comments_ibfk_3` FOREIGN KEY (`parent_comment_id`) REFERENCES `post_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `post_likes_ibfk_3` FOREIGN KEY (`comment_id`) REFERENCES `post_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
