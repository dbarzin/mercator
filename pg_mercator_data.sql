--
-- PostgreSQL database dump
--

-- Dumped from database version 12.10 (Ubuntu 12.10-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.10 (Ubuntu 12.10-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: activities; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.activities (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	Activité 1	<p>Description de l'activité 1</p>	2020-06-10 15:20:42	2020-06-10 15:20:42	\N
2	Activité 2	<p>Description de l'activité de test</p>	2020-06-10 17:44:26	2020-06-13 06:03:26	\N
3	Activité 3	<p>Description de l'activité 3</p>	2020-06-13 06:57:08	2020-06-13 06:57:08	\N
4	Activité 4	<p>Description de l'acivité 4</p>	2020-06-13 06:57:24	2020-06-13 06:57:24	\N
5	Activité principale	<p>Description de l'activité principale</p>	2020-08-15 06:19:53	2020-08-15 06:19:53	\N
6	AAA	test a1	2021-03-22 20:06:55	2021-03-22 20:07:00	2021-03-22 20:07:00
7	AAA	test AAA	2021-03-22 20:13:43	2021-03-22 20:14:05	2021-03-22 20:14:05
8	AAA	test 2 aaa	2021-03-22 20:14:16	2021-03-22 20:14:45	2021-03-22 20:14:45
9	AAA1	test 3 AAA	2021-03-22 20:14:40	2021-03-22 20:19:09	2021-03-22 20:19:09
10	Activité 0	<p>Description de l'activité zéro</p>	\N	2021-05-15 09:40:16	\N
11	test	dqqsd	2021-08-02 22:03:46	2021-09-22 12:59:48	2021-09-22 12:59:48
\.


--
-- Data for Name: operations; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.operations (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	Operation 1	<p>Description de l'opération</p>	2020-06-13 02:02:42	2020-06-13 02:02:42	\N
2	Operation 2	<p>Description de l'opération</p>	2020-06-13 02:02:58	2020-06-13 02:02:58	\N
3	Operation 3	<p>Desciption de l'opération</p>	2020-06-13 02:03:11	2020-07-15 16:34:52	\N
4	Operation 4	\N	2020-07-15 16:34:02	2020-07-15 16:34:02	\N
5	Master operation	<p>Opération maitre</p>	2020-08-15 06:01:40	2020-08-15 06:01:40	\N
\.


--
-- Data for Name: activity_operation; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.activity_operation (activity_id, operation_id) FROM stdin;
2	3
1	1
1	2
4	3
3	1
1	5
5	1
6	1
10	1
\.


--
-- Data for Name: macro_processuses; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.macro_processuses (id, name, description, io_elements, security_need_c, owner, created_at, updated_at, deleted_at, security_need_i, security_need_a, security_need_t) FROM stdin;
1	Macro-Processus 1	<p>Description du macro-processus de test.</p>	<p>Entrant :</p><ul><li>donnée 1</li><li>donnée 2</li><li>donnée 3</li></ul><p>Sortant :</p><ul><li>donnée 4</li><li>donnée 5</li></ul>	4	Nestor	2020-06-10 09:02:16	2021-05-14 15:29:36	\N	3	2	1
2	Macro-Processus 2	<p>Description du macro-processus</p>	<p>Valeur de test</p>	1	Simon	2020-06-13 03:03:42	2021-05-14 09:21:10	\N	2	3	4
3	Valeur de test	<p>Valeur de test</p>	<p>Valeur de test</p>	3	All	2020-08-09 07:32:37	2020-08-24 16:45:57	2020-08-24 16:45:57	\N	\N	\N
4	Proc3	<p>dfsdf</p>	<p>dsfsdf</p>	0	\N	2020-08-31 16:13:55	2020-08-31 16:31:29	2020-08-31 16:31:29	\N	\N	\N
5	Proc4	<p>dfsdf</p>	<p>dsfsdf</p>	0	\N	2020-08-31 16:19:32	2020-08-31 16:31:29	2020-08-31 16:31:29	\N	\N	\N
6	Proc5	<p>dfsdf</p>	<p>dsfsdf</p>	0	\N	2020-08-31 16:29:20	2020-08-31 16:31:29	2020-08-31 16:31:29	\N	\N	\N
7	MP1	<p>sdfsdfs</p>	\N	0	\N	2020-08-31 16:31:40	2020-08-31 16:38:31	2020-08-31 16:38:31	\N	\N	\N
8	MP2	<p>sdfsdfs</p>	\N	0	\N	2020-08-31 16:37:39	2020-08-31 16:38:31	2020-08-31 16:38:31	\N	\N	\N
9	MP3	<p>sdfsdfs</p>	\N	0	\N	2020-08-31 16:38:06	2020-08-31 16:38:31	2020-08-31 16:38:31	\N	\N	\N
10	Macro-Processus 3	<p>Description du troisième macro-processus</p>	<ul><li>un</li><li>deux</li><li>trois</li><li>quatre</li></ul>	2	Nestor	2020-11-24 09:21:38	2021-05-14 09:20:55	\N	2	2	2
11	Macro-Processus 4	<p>Description du macro processus quatre</p>	<ul><li>crayon</li><li>stylos</li><li>gommes</li></ul>	1	Pirre	2021-05-14 09:19:51	2021-09-22 13:00:08	2021-09-22 13:00:08	1	1	1
\.


--
-- Data for Name: processes; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.processes (id, identifiant, description, owner, security_need_c, in_out, dummy, created_at, updated_at, deleted_at, macroprocess_id, security_need_i, security_need_a, security_need_t) FROM stdin;
1	Processus 1	<p>Description du processus 1</p>	Ched	3	<ul><li>pommes</li><li>poires</li><li>cerise</li></ul><p>&lt;test</p>	\N	2020-06-17 16:36:24	2021-09-22 13:38:57	\N	1	2	3	1
2	Processus 2	<p>Description du processus 2</p>	Ched	3	<p>1 2 3 4 5 6</p>	\N	2020-06-17 16:36:58	2021-09-22 12:59:14	\N	10	4	2	4
3	Processus 3	<p>Description du processus 3</p>	Johan	3	<p>a,b,c</p><p>d,e,f</p>	\N	2020-07-01 17:50:27	2021-08-17 10:22:13	\N	2	2	3	1
4	Processus 4	<p>Description du processus 4</p>	Paul	4	<ul><li>chaussettes</li><li>pantalon</li><li>chaussures</li></ul>	\N	2020-08-18 17:00:36	2021-08-17 10:22:29	\N	2	2	2	2
5	totoat	<p>tto</p>	\N	1	<p>sgksdùmfk</p>	\N	2020-08-27 15:16:56	2020-08-27 15:17:01	2020-08-27 15:17:01	1	\N	\N	\N
6	ptest	<p>description de ptest</p>	\N	0	<p>toto titi tutu</p>	\N	2020-08-29 13:10:23	2020-08-29 13:10:28	2020-08-29 13:10:28	\N	\N	\N	\N
7	ptest2	<p>fdfsdfsdf</p>	\N	1	<p>fdfsdfsd</p>	\N	2020-08-29 13:16:42	2020-08-29 13:17:09	2020-08-29 13:17:09	1	\N	\N	\N
8	ptest3	<p>processus de test 3</p>	CHEM - Facturation	3	<p>dsfsdf sdf sdf sd fsd fsd f s</p>	\N	2020-08-29 13:19:13	2020-08-29 13:20:59	2020-08-29 13:20:59	1	\N	\N	\N
9	Processus 5	<p>Description du cinquième processus</p>	Paul	4	<ul><li>chat</li><li>chien</li><li>poisson</li></ul>	\N	2021-05-14 09:10:02	2021-09-22 12:59:14	\N	10	3	2	3
10	Proc 6	\N	\N	0	\N	\N	2021-10-08 21:18:28	2021-10-08 21:28:38	2021-10-08 21:28:38	\N	0	0	0
\.


--
-- Data for Name: activity_process; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.activity_process (process_id, activity_id) FROM stdin;
1	1
1	2
2	3
2	4
3	2
3	5
4	5
5	4
6	4
7	3
8	4
9	3
1	10
\.


--
-- Data for Name: actors; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.actors (id, name, nature, type, contact, created_at, updated_at, deleted_at) FROM stdin;
1	Jean	Personne	interne	jean@testdomain.org	2020-06-14 13:02:22	2021-05-16 19:37:49	\N
2	Service 1	Groupe	interne	\N	2020-06-14 13:02:39	2020-06-17 16:43:42	2020-06-17 16:43:42
3	Service 2	Groupe	Interne	\N	2020-06-14 13:02:54	2020-06-17 16:43:46	2020-06-17 16:43:46
4	Pierre	Personne	interne	email : pierre@testdomain.com	2020-06-17 16:44:01	2021-05-16 19:38:19	\N
5	Jacques	personne	interne	Téléphone 1234543423	2020-06-17 16:44:23	2020-06-17 16:44:23	\N
6	Fournisseur 1	entité	externe	Tel : 1232 32312	2020-06-17 16:44:50	2020-06-17 16:44:50	\N
\.


--
-- Data for Name: actor_operation; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.actor_operation (operation_id, actor_id) FROM stdin;
2	1
1	1
1	4
2	5
3	6
5	4
\.


--
-- Data for Name: zone_admins; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.zone_admins (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	Enreprise	<p>Zone d'administration de l'entreprise</p>	2020-07-03 09:49:03	2021-05-23 15:07:18	\N
\.


--
-- Data for Name: annuaires; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.annuaires (id, name, description, solution, created_at, updated_at, deleted_at, zone_admin_id) FROM stdin;
1	AD01	<p>Annuaire principal&nbsp;</p>	Acive Directory	2020-07-03 09:49:37	2022-03-22 20:33:39	\N	1
2	Mercator	<p>Cartographie du système d'information</p>	Logiciel développé maison	2020-07-03 12:24:48	2020-07-13 17:12:59	\N	1
\.


--
-- Data for Name: application_blocks; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.application_blocks (id, name, description, responsible, created_at, updated_at, deleted_at) FROM stdin;
1	Bloc applicatif 1	<p>Description du bloc applicatif</p>	Jean Pierre	2020-06-13 06:09:01	2020-06-13 06:09:01	\N
2	Bloc applicatif 2	<p>Second bloc applicatif.</p>	Marcel pierre	2020-06-13 06:10:52	2020-06-17 18:13:33	\N
3	Bloc applicatif 3	<p>Description du block applicatif 3</p>	Nestor	2020-08-29 14:00:10	2022-03-20 18:53:29	\N
\.


--
-- Data for Name: application_modules; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.application_modules (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	Module 1	<p>Description du module 1</p>	2020-06-13 11:55:34	2020-06-13 11:55:34	\N
2	Module 2	<p>Description du module 2</p>	2020-06-13 11:55:45	2020-06-13 11:55:45	\N
3	Module 3	<p>Description du module 3</p>	2020-06-13 11:56:00	2020-06-13 11:56:00	\N
4	Module 4	<p>Description du module 4</p>	2020-06-13 11:56:10	2020-06-13 11:56:10	\N
5	Module 5	<p>Description du module 5</p>	2020-06-13 11:56:20	2020-06-13 11:56:20	\N
6	Module 6	<p>Description du module 6</p>	2020-06-13 11:56:32	2020-06-13 11:56:32	\N
\.


--
-- Data for Name: application_services; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.application_services (id, description, exposition, name, created_at, updated_at, deleted_at) FROM stdin;
1	<p>Descrition du service applicatif 1</p>	cloud	SRV-1	2020-06-13 11:35:31	2021-08-03 20:50:33	\N
2	<p>Description du service 2</p>	local	Service 2	2020-06-13 11:35:48	2020-06-13 11:35:48	\N
3	<p>Description du service 3</p>	local	Service 3	2020-06-13 11:36:04	2020-06-13 11:43:05	\N
4	<p>Description du service 4</p>	local	Service 4	2020-06-13 11:36:17	2020-06-13 11:36:17	\N
5	<p>Service applicatif 4</p>	Extranet	SRV-4	2021-08-02 16:11:43	2021-08-17 10:24:10	\N
6	<p>Service applicatif 4</p>	\N	SRV-5	2021-08-02 16:12:19	2021-08-02 16:12:19	\N
7	<p>Service applicatif 4</p>	\N	SRV-6	2021-08-02 16:12:56	2021-08-02 16:12:56	\N
8	<p>The service 99</p>	local	SRV-99	2021-08-02 16:13:39	2021-09-07 18:53:36	\N
9	<p>Service applicatif 4</p>	\N	SRV-9	2021-08-02 16:14:27	2021-08-02 16:14:27	\N
10	<p>Service applicatif 4</p>	Extranet	SRV-10	2021-08-02 16:15:21	2021-08-17 10:24:20	\N
11	<p>Service applicatif 4</p>	Extranet	SRV-11	2021-08-02 16:16:34	2021-08-17 10:24:28	\N
\.


--
-- Data for Name: application_module_application_service; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.application_module_application_service (application_service_id, application_module_id) FROM stdin;
4	1
4	2
3	3
2	4
1	5
1	6
5	2
5	3
6	2
6	3
7	2
7	3
8	2
8	3
9	2
9	3
10	2
10	3
11	2
11	3
\.


--
-- Data for Name: entities; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.entities (id, name, security_level, contact_point, description, created_at, updated_at, deleted_at, is_external) FROM stdin;
1	MegaNet System	<p>ISO 27001</p>	<p>Helpdek<br>27, Rue des poire&nbsp;<br>12043 Mire-en-Mare le Bains</p><p>helpdes@menetsys.org</p>	<p>Fournisseur équipement réseau</p>	2020-05-21 04:30:59	2022-05-20 17:30:00	\N	t
2	Entité1	<p>Néant</p>	<ul><li>Commercial</li><li>Service Delivery</li><li>Helpdesk</li></ul>	<p>Entité de tests1</p>	2020-05-21 04:31:17	2021-05-23 14:59:11	\N	f
3	CHdN	3	RSSI du CHdN	<p>Centre Hospitalier du Nord</p>	2020-05-21 04:43:41	2021-05-13 10:20:32	2021-05-13 10:20:32	f
4	Entité3	<p>ISO 9001</p>	<p>Point de contact de la troisième entité</p>	<p>Description de la troisième entité.</p>	2020-05-21 04:44:03	2021-07-20 21:20:37	\N	f
5	entité6	<p>Néant</p>	<p>support_informatque@entite6.fr</p>	<p>Description de l'entité six</p>	2020-05-21 04:44:18	2021-05-23 15:03:15	\N	f
6	Entité4	<p>ISO 27001</p>	<p>Pierre Pinon<br>Tel: 00 34 392 484 22</p>	<p>Description de l'entté quatre</p>	2020-05-21 04:45:14	2021-05-23 15:01:17	\N	f
7	Entité5	<p>Néant</p>	<p>Servicdesk@entite5.fr</p>	<p>Description de l'entité 5</p>	2020-05-21 05:38:41	2021-05-23 15:02:16	\N	f
8	Entité2	<p>ISO 27001</p>	<p>Point de contact de l'entité 2</p>	<p>Description de l'entité 2</p>	2020-05-21 05:54:22	2021-05-23 15:00:03	\N	f
9	NetworkSys	<p>ISO 27001</p>	<p>support@networksys.fr</p>	<p>Description de l’entité NetworkSys</p>	2020-05-21 08:25:15	2022-05-20 17:30:00	\N	t
10	Agence eSanté	<p>Néant</p>	<p>helpdesk@esante.lu</p>	<p>Agence Nationale des information partagées dans le domaine de la santé</p><ul><li>a</li><li>b</li><li>c</li></ul><p>+-------+<br>+ TOTO +<br>+-------+</p><p>&lt;&lt;&lt;&lt;&lt;&lt; &gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;</p>	2020-05-21 08:25:26	2021-05-13 10:20:32	2021-05-13 10:20:32	f
11	Test	4	\N	<p>Test</p>	2020-07-02 17:37:29	2020-07-02 17:37:44	2020-07-02 17:37:44	f
12	Pierre et fils	<p>Certifications :&nbsp;<br>- ISO 9001<br>- ISO 27001<br>- ISO 31000</p>	<p>Paul Pierre<br>Gérant<br>00 33 4943 432 423</p>	<p>Description de l'entité de test</p>	2020-07-06 15:37:54	2022-05-20 17:30:00	\N	t
13	Nestor	<p>Haut niveau</p>	<p>Paul, Pierre et Jean</p>	<p>Description de Nestor</p>	2020-08-12 18:11:31	2020-08-12 18:12:13	\N	f
14	0001	\N	\N	<p>rrzerze</p>	2021-06-15 17:16:31	2021-06-15 17:17:08	2021-06-15 17:17:08	f
15	002	\N	\N	<p>sdqsfsd</p>	2021-06-15 17:16:41	2021-06-15 17:17:08	2021-06-15 17:17:08	f
16	003	\N	\N	<p>dsqdsq</p>	2021-06-15 17:16:51	2021-06-15 17:17:08	2021-06-15 17:17:08	f
17	004	\N	\N	<p>dqqqsdqs</p>	2021-06-15 17:17:01	2021-06-15 17:17:08	2021-06-15 17:17:08	f
18	Acme corp.	<p>None sorry...</p>	<p>Do not call me, I will call you back.</p>	<p>Looney tunes academy</p>	2021-09-07 20:07:16	2022-05-20 17:30:00	\N	t
19	HAL	<p>Top security certification</p>	<p>hal@corp.com</p>	<p>Very big HAL corporation</p>	2021-09-07 20:08:56	2021-09-07 20:09:17	\N	f
20	ATest1	\N	\N	\N	2022-04-25 14:43:46	2022-04-25 14:44:02	2022-04-25 14:44:02	f
21	ATest2	\N	\N	\N	2022-04-25 14:43:56	2022-04-25 14:44:02	2022-04-25 14:44:02	f
\.


--
-- Data for Name: m_applications; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.m_applications (id, name, description, security_need_c, responsible, type, technology, external, users, created_at, updated_at, deleted_at, entity_resp_id, application_block_id, documentation, security_need_i, security_need_a, security_need_t, version, functional_referent, editor, install_date, update_date) FROM stdin;
1	Application 1	<p>Description de l'application 1</p>	1	RSSI	logiciel	Microsoft	\N	> 20	2020-06-14 11:20:15	2022-03-20 18:53:29	\N	2	3	//Documentation/application1.docx	1	1	1	1.2	\N	\N	\N	\N
2	Application 2	<p><i>Description</i> de l'<strong>application</strong> 2</p>	2	RSSI	progiciel	martian	SaaS	>100	2020-06-14 11:31:16	2022-02-06 16:52:36	\N	18	1	None	2	2	2	1.0	\N	\N	\N	\N
3	Application 3	<p>Test application 3</p>	1	RSSI	progiciel	Microsoft	Interne	>100	2020-06-17 19:33:41	2021-05-15 10:06:53	\N	12	2	Aucune	2	3	3	\N	\N	\N	\N	\N
4	Application 4	<p>Description app4</p>	2	RSSI	logiciel	Microsoft	Internl	>100	2020-08-11 16:13:02	2021-07-11 10:59:57	\N	1	2	None	2	3	2	\N	\N	\N	\N	\N
5	CUST AP01	<p>Customer appication</p>	0	\N	\N	web	\N	\N	2020-08-22 06:58:18	2020-08-26 16:56:20	2020-08-26 16:56:20	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
6	totototo	\N	0	\N	\N	totottoo	\N	\N	2020-08-22 06:59:26	2020-08-22 06:59:43	2020-08-22 06:59:43	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
7	Windows Word	<p>Description de l'application</p>	3	Nestor	artificiel	client lourd	\N	>100	2020-08-23 10:20:34	2020-08-26 16:56:23	2020-08-26 16:56:23	10	2	\N	\N	\N	\N	\N	\N	\N	\N	\N
8	Application 99	\N	1	André	progiciel	client lourd	SaaS	>100	2020-08-23 12:08:02	2020-08-26 16:56:13	2020-08-26 16:56:13	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
9	Test33	<p>fsfsdfsd</p>	0	Nestor	progiciel	martian	\N	\N	2020-08-26 16:54:05	2020-08-26 16:54:35	2020-08-26 16:54:35	10	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
10	Test33R	<p>fsfsdfsd</p>	0	Nestor	progiciel	martian	\N	\N	2020-08-26 16:54:28	2020-08-26 16:54:39	2020-08-26 16:54:39	10	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
11	SuperApp	<p>Supper application</p>	0	RSSI	logiciel	martian	\N	\N	2021-04-12 16:54:57	2021-04-12 19:10:44	2021-04-12 19:10:44	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
12	SuperApp	<p>Super super application !</p>	1	RSSI	Web	Oracle	Interne	\N	2021-04-12 19:10:59	2021-06-23 21:33:15	\N	1	2	\N	1	1	1	\N	\N	\N	\N	\N
13	test application	\N	0	\N	\N	\N	\N	\N	2021-05-07 10:23:59	2021-05-07 10:24:03	2021-05-07 10:24:03	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
14	Windows Calc	<p>Calculatrice windows</p>	2	RSSI	logiciel	Microsoft	Internl	\N	2021-05-13 10:15:27	2022-03-20 18:53:29	\N	1	3	\N	0	0	0	\N	\N	\N	\N	\N
15	Compta	<p>Application de comptabilité</p>	3	RSSI	progiciel	Microsoft	Interne	>100	2021-05-15 09:53:15	2021-05-15 09:53:15	\N	1	2	\N	4	2	3	\N	\N	\N	\N	\N
16	Queue Manager	<p>Queue manager</p>	4	RSSI	logiciel	Internal Dev	Interne	>100	2021-08-02 17:17:11	2021-08-02 17:18:32	\N	1	1	//Portal/QueueManager.doc	4	4	4	\N	\N	\N	\N	\N
17	test	\N	0	\N	\N	\N	\N	\N	2021-10-10 13:03:24	2021-10-10 13:03:24	\N	\N	\N	\N	0	0	0	\N	\N	\N	\N	\N
18	Application 42	<p>The Ultimate Question of Life, the Universe and Everything</p>	-1	Nestor	\N	\N	\N	\N	2021-11-15 17:03:20	2021-12-11 11:06:18	\N	\N	\N	\N	-1	-1	0	\N	\N	\N	\N	\N
\.


--
-- Data for Name: application_service_m_application; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.application_service_m_application (m_application_id, application_service_id) FROM stdin;
2	3
2	4
1	3
15	2
15	3
1	1
4	11
4	5
2	7
4	7
1	10
16	10
16	11
16	5
16	6
16	7
16	9
16	1
16	2
16	3
16	4
16	8
\.


--
-- Data for Name: audit_logs; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.audit_logs (id, description, subject_id, subject_type, user_id, properties, host, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sites; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.sites (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	Site A	<p>Description du site A</p>	2020-06-21 06:36:41	2020-06-21 06:36:41	\N
2	Site B	<p>Description du site B</p>	2020-06-21 06:36:53	2020-06-21 06:36:53	\N
3	Site C	<p>Description du Site C</p>	2020-06-21 06:37:05	2020-06-21 06:37:05	\N
4	Test1	<p>site de test</p>	2020-07-24 21:12:29	2020-07-24 21:12:56	2020-07-24 21:12:56
5	testsite	<p>description here</p>	2021-04-12 17:31:40	2021-04-12 17:32:04	2021-04-12 17:32:04
6	Site Z	\N	2021-06-18 07:36:03	2021-10-19 18:51:22	2021-10-19 18:51:22
7	Site 0	\N	2021-06-18 07:36:12	2021-08-17 19:52:52	2021-08-17 19:52:52
\.


--
-- Data for Name: buildings; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.buildings (id, name, description, created_at, updated_at, deleted_at, site_id, camera, badge) FROM stdin;
1	Building 1	<p>Description du building 1</p>	2020-06-21 06:37:21	2020-06-21 06:47:41	\N	1	\N	\N
2	Building 2	<p>Description du building 2</p>	2020-06-21 06:37:36	2020-07-25 08:26:13	\N	1	\N	\N
3	Building 3	<p>Description du building 3</p>	2020-06-21 06:37:48	2020-07-25 08:26:03	\N	2	\N	\N
4	Building 4	<p>Description du building 4</p>	2020-06-21 06:38:03	2020-07-25 08:25:54	\N	2	\N	\N
5	Building 5	<p>Descripion du building 5</p>	2020-06-21 06:38:16	2020-07-25 08:26:26	\N	3	\N	\N
6	Test building	<p>Description</p>	2020-07-24 21:12:48	2020-07-24 21:14:08	2020-07-24 21:14:08	4	\N	\N
7	Building 0	<p>Le building zéro</p>	2020-08-21 15:10:15	2020-10-02 09:38:55	\N	1	\N	\N
8	test	<p>test</p>	2020-11-06 14:44:22	2020-11-06 15:26:18	2020-11-06 15:26:18	\N	t	f
9	test2	<p>test2</p>	2020-11-06 14:59:45	2020-11-06 15:06:50	2020-11-06 15:06:50	\N	\N	\N
10	test3	<p>fdfsdfsd</p>	2020-11-06 15:07:07	2020-11-06 15:26:18	2020-11-06 15:26:18	\N	\N	\N
11	test4	\N	2020-11-06 15:25:52	2020-11-06 15:26:18	2020-11-06 15:26:18	\N	f	f
\.


--
-- Data for Name: bays; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.bays (id, name, description, created_at, updated_at, deleted_at, room_id) FROM stdin;
1	BAIE 101	<p>Description de la baie 101</p>	2020-06-21 06:56:01	2021-10-19 18:45:21	\N	7
2	BAIE 102	<p>Desciption baie 102</p>	2020-06-21 06:56:20	2020-06-21 06:56:20	\N	1
3	BAIE 103	<p>Descripton baid 103</p>	2020-06-21 06:56:38	2020-06-21 06:56:38	\N	1
4	BAIE 201	<p>Description baie 201</p>	2020-06-21 06:56:55	2020-06-21 06:56:55	\N	2
5	BAIE 301	<p>Baie 301</p>	2020-07-15 20:03:07	2020-07-15 20:03:07	\N	3
6	BAIE 501	<p>Baie 501</p>	2020-07-15 20:10:23	2020-07-15 20:10:23	\N	5
\.


--
-- Data for Name: wifi_terminals; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.wifi_terminals (id, name, description, type, created_at, updated_at, deleted_at, site_id, building_id) FROM stdin;
1	WIFI_01	<p>Borne wifi 01</p>	Alcatel 3500	2020-07-22 16:44:37	2020-07-22 16:44:37	\N	1	2
2	WIFI_02	<p>Borne Wifi 2</p>	ALCALSYS 3001	2021-06-07 16:37:47	2021-06-07 16:37:47	\N	2	1
3	WIFI_03	<p>Borne Wifi 3</p>	SYSTEL 3310	2021-06-07 16:42:29	2021-06-07 16:43:18	\N	3	4
\.


--
-- Data for Name: bay_wifi_terminal; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.bay_wifi_terminal (wifi_terminal_id, bay_id) FROM stdin;
\.


--
-- Data for Name: cartographer_m_application; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.cartographer_m_application (id, user_id, m_application_id, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: certificates; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.certificates (id, name, type, description, start_validity, end_validity, created_at, updated_at, deleted_at, status) FROM stdin;
1	CERT01	DES3	<p>Certificat 01</p>	2020-10-27	2022-01-01	2021-07-14 10:28:47	2022-02-08 16:25:10	\N	0
2	CERT02	AES 256	<p>Certificat numéro 02</p>	2021-07-14	2021-07-17	2021-07-14 10:33:33	2021-07-14 16:14:12	\N	\N
3	CERT03	AES 256	<p>Certificat numéro 3</p>	2021-09-23	2021-11-11	2021-07-14 12:35:41	2021-09-23 16:11:34	\N	\N
4	CERT04	DES3	<p>Certificat interne DES 3</p>	\N	\N	2021-07-14 12:40:15	2021-07-14 12:40:15	\N	\N
5	CERT05	RSA 128	<p>Clé 05 avec RSA</p>	\N	\N	2021-07-14 12:45:00	2021-07-14 12:45:00	\N	\N
6	CERT07	DES3	<p>cert 7</p>	\N	\N	2021-07-14 14:44:12	2021-07-14 14:44:12	\N	\N
7	CERT08	DES3	<p>Master cert 08</p>	2021-06-15	2022-08-11	2021-08-11 20:33:42	2021-08-11 20:33:42	\N	\N
8	CERT09	DES3	<p>Test cert nine</p>	2021-09-25	2021-09-26	2021-09-23 16:17:20	2021-09-23 16:17:20	\N	\N
\.


--
-- Data for Name: logical_servers; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.logical_servers (id, name, description, net_services, configuration, created_at, updated_at, deleted_at, operating_system, address_ip, cpu, memory, environment, disk, install_date, update_date) FROM stdin;
1	SRV-1	<p>Description du serveur 1</p>	DNS, HTTP, HTTPS	<p>Configuration du serveur 1</p>	2020-07-12 18:57:42	2021-08-17 15:13:21	\N	Windows 3.1	10.10.1.1, 10.10.10.1	2	8	PROD	60	\N	\N
2	SRV-2	<p>Description du serveur 2</p>	HTTPS, SSH	<p>Configuration par défaut</p>	2020-07-30 12:00:16	2021-08-17 20:17:41	\N	Windows 10	10.50.1.2	2	5	PROD	100	\N	\N
3	SRV-3	<p>Description du serveur 3</p>	HTTP, HTTPS	\N	2021-08-26 16:33:03	2021-08-26 16:33:38	\N	Ubuntu 20.04	10.70.8.3	4	16	PROD	80	\N	\N
4	SRV-42	<p><i>The Ultimate Question of Life, the Universe and Everything</i></p>	\N	<p>Full configuration</p>	2021-11-15 17:03:59	2022-03-20 12:39:54	\N	OS 42	10.0.0.42	42	42 G	PROD	42	\N	\N
5	SRV-4	<p>Description du serveur 4</p>	\N	\N	2022-05-02 18:43:02	2022-05-02 18:49:34	\N	Ubunti 22.04 LTS	10.10.3.2	4	2	Dev	\N	2022-05-01 20:47:41	2022-05-02 20:47:47
\.


--
-- Data for Name: certificate_logical_server; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.certificate_logical_server (certificate_id, logical_server_id) FROM stdin;
4	1
5	2
1	1
2	1
3	1
7	1
\.


--
-- Data for Name: certificate_m_application; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.certificate_m_application (certificate_id, m_application_id) FROM stdin;
8	4
\.


--
-- Data for Name: databases; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.databases (id, name, description, responsible, type, security_need_c, external, created_at, updated_at, deleted_at, entity_resp_id, security_need_i, security_need_a, security_need_t) FROM stdin;
1	Database 1	<p>Description Database 1</p>	Paul	MySQL	1	Interne	2020-06-17 16:18:48	2021-05-14 12:19:45	\N	2	2	3	4
3	Database 2	<p>Description database 2</p>	Paul	MySQL	1	Interne	2020-06-17 16:19:24	2021-05-14 12:29:47	\N	1	1	1	1
4	MainDB	<p>description de la base de données</p>	Paul	Oracle	2	Interne	2020-07-01 17:08:57	2021-08-20 03:52:23	\N	1	2	2	2
5	DB Compta	<p>Base de donnée de la compta</p>	Paul	MariaDB	2	Interne	2020-08-24 17:58:23	2022-03-21 18:13:10	\N	18	2	2	2
6	Data Warehouse	<p>Base de données du datawarehouse</p>	Jean	Oracle	2	Interne	2021-05-14 12:24:02	2022-03-21 18:13:24	\N	1	2	2	2
\.


--
-- Data for Name: database_entity; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.database_entity (database_id, entity_id) FROM stdin;
1	1
3	1
4	1
5	1
6	1
\.


--
-- Data for Name: information; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.information (id, name, description, owner, administrator, storage, security_need_c, sensitivity, constraints, created_at, updated_at, deleted_at, security_need_i, security_need_a, security_need_t) FROM stdin;
1	Information 1	<p>Description de l'information 1</p>	Luc	\N	externe	1	Donnée à caractère personnel	<p>Description des contraintes règlementaires et normatives</p>	2020-06-13 02:06:43	2021-11-04 08:43:27	\N	3	2	2
2	information 2	<p>Description de l'information</p>	Nestor	Nom de l'administrateur	externe	2	Donnée à caractère personnel	\N	2020-06-13 02:09:13	2021-08-19 18:42:53	\N	1	1	1
3	information 3	<p>Descripton de l'information 3</p>	Paul	Jean	Local	4	Donnée à caractère personnel	\N	2020-06-13 02:10:07	2021-09-28 19:42:07	\N	4	3	4
4	Information de test	<p>decription du test</p>	RSSI	Paul	Local	1	Technical	\N	2020-07-01 17:00:37	2021-08-19 18:45:52	\N	1	1	1
5	Données du client	<p>Données d'identification du client</p>	Nestor	Paul	Local	2	Donnée à caractère personnel	<p>RGPD</p>	2021-05-14 12:50:09	2022-03-21 18:12:30	\N	2	2	2
\.


--
-- Data for Name: database_information; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.database_information (database_id, information_id) FROM stdin;
1	1
1	2
1	3
3	2
3	3
5	1
4	2
6	2
6	3
5	5
\.


--
-- Data for Name: database_m_application; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.database_m_application (m_application_id, database_id) FROM stdin;
2	3
3	4
3	1
4	5
4	6
15	5
15	4
16	1
\.


--
-- Data for Name: dhcp_servers; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.dhcp_servers (id, name, description, created_at, updated_at, deleted_at, address_ip) FROM stdin;
\.


--
-- Data for Name: dnsservers; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.dnsservers (id, name, description, created_at, updated_at, deleted_at, address_ip) FROM stdin;
\.


--
-- Data for Name: domaine_ads; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.domaine_ads (id, name, description, domain_ctrl_cnt, user_count, machine_count, relation_inter_domaine, created_at, updated_at, deleted_at) FROM stdin;
1	Dom1	<p>Domaine AD1</p>	3	2000	800	Non	2020-07-03 09:51:06	2020-07-03 09:51:06	\N
2	test domain	<p>this is a test</p>	\N	\N	\N	\N	2021-05-27 15:24:52	2021-05-27 15:29:15	2021-05-27 15:29:15
3	Dom2	<p>Second domaine active directory</p>	2	100	1	Néant	2021-05-27 15:29:43	2021-05-27 15:29:43	\N
4	Dom5	<p>Domaine cinq</p>	\N	\N	\N	\N	2021-05-27 15:39:08	2021-05-27 15:39:08	\N
5	Dom4	<p>Domaine quatre</p>	\N	\N	\N	\N	2021-05-27 15:39:20	2021-05-27 15:39:20	\N
\.


--
-- Data for Name: forest_ads; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.forest_ads (id, name, description, created_at, updated_at, deleted_at, zone_admin_id) FROM stdin;
1	AD1	<p>Foret de l'AD 1</p>	2020-07-03 09:50:07	2020-07-03 09:50:29	\N	1
2	AD2	<p>Foret de l'AD2</p>	2020-07-03 09:50:19	2020-07-03 09:50:19	\N	1
\.


--
-- Data for Name: domaine_ad_forest_ad; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.domaine_ad_forest_ad (forest_ad_id, domaine_ad_id) FROM stdin;
1	1
2	1
1	3
2	5
1	4
\.


--
-- Data for Name: entity_m_application; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.entity_m_application (m_application_id, entity_id) FROM stdin;
2	1
5	1
7	2
9	1
10	1
2	2
11	1
1	2
1	8
3	8
4	8
4	4
16	2
\.


--
-- Data for Name: entity_process; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.entity_process (process_id, entity_id) FROM stdin;
1	1
2	1
3	1
1	13
3	13
4	1
7	3
9	4
2	8
4	6
4	7
9	5
1	9
2	9
3	9
4	9
9	9
1	12
1	2
4	18
3	19
\.


--
-- Data for Name: external_connected_entities; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.external_connected_entities (id, name, responsible_sec, contacts, created_at, updated_at, deleted_at) FROM stdin;
1	Entité externe 1	Nestor	Marcel	2020-07-23 09:59:25	2020-07-23 09:59:25	\N
2	Entité externe 2	Philippe	it@external.corp	2021-08-17 19:52:26	2021-08-17 19:52:26	\N
\.


--
-- Data for Name: networks; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.networks (id, name, protocol_type, responsible, responsible_sec, security_need_c, description, created_at, updated_at, deleted_at, security_need_i, security_need_a, security_need_t) FROM stdin;
1	Réseau 1	TCP	Pierre	Paul	1	<p>Description du réseau 1</p>	2020-06-23 14:34:14	2021-09-22 12:20:11	\N	2	3	4
2	Réseau 2	TCP	Johan	Jean-Marc	1	<p>Description du réseau 2</p>	2020-07-01 17:45:41	2021-09-22 12:21:23	\N	1	1	1
3	test	\N	\N	\N	4	<p>réseau test</p>	2021-09-22 12:30:23	2021-09-22 12:30:29	2021-09-22 12:30:29	4	4	4
\.


--
-- Data for Name: external_connected_entity_network; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.external_connected_entity_network (external_connected_entity_id, network_id) FROM stdin;
1	1
2	2
\.


--
-- Data for Name: fluxes; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.fluxes (id, name, description, created_at, updated_at, deleted_at, application_source_id, service_source_id, module_source_id, database_source_id, application_dest_id, service_dest_id, module_dest_id, database_dest_id, crypted, bidirectional) FROM stdin;
2	FluxA	<p>Description du flux A</p>	2020-06-17 16:50:59	2021-09-29 08:02:26	\N	1	\N	\N	\N	2	\N	\N	\N	f	t
3	FluxC	<p>Flux de test</p>	2020-07-07 15:58:22	2021-09-23 19:04:30	\N	2	\N	\N	\N	3	\N	\N	\N	t	\N
4	FluxB	<p>Flux de test 3</p>	2020-07-07 16:01:10	2021-09-29 08:07:54	\N	\N	\N	4	\N	2	\N	\N	\N	t	t
5	Sync_DB	<p>Description du flux 01</p>	2020-07-23 12:44:35	2021-10-10 13:16:32	\N	\N	\N	\N	\N	\N	\N	\N	3	t	f
6	Flux_MOD_01	<p>Fuld module</p>	2020-07-23 12:48:20	2021-09-29 07:59:35	\N	\N	\N	3	\N	\N	\N	2	\N	f	f
7	Flux_SER_01	Description du flux service 01	2020-07-23 12:51:41	2020-07-23 12:51:41	\N	\N	3	\N	\N	\N	4	\N	\N	f	\N
8	Fulx 07	Description du flux 07	2020-09-05 06:56:57	2020-09-05 06:57:36	\N	\N	1	\N	\N	\N	2	\N	\N	t	\N
9	FLux DB_02	<p>Description du flux 2</p>	2020-09-05 07:12:05	2021-09-29 07:59:19	\N	2	\N	\N	\N	\N	\N	2	\N	f	f
10	SRV10_to_SRV11	<p>Transfert from SRV10 to SRV11</p>	2021-08-02 17:13:31	2021-08-02 17:13:31	\N	\N	10	\N	\N	\N	11	\N	\N	f	\N
11	SRV4_to_SRV10	\N	2021-08-02 17:13:57	2021-08-02 17:13:57	\N	\N	5	\N	\N	\N	10	\N	\N	t	\N
12	SRV6_to_SRV10	<p>service 6 to service 10</p>	2021-08-02 17:14:36	2021-08-02 17:14:36	\N	\N	7	\N	\N	\N	10	\N	\N	t	\N
13	Syncy System	\N	2021-08-02 20:01:21	2021-08-02 20:01:21	\N	\N	10	\N	\N	\N	\N	\N	\N	t	\N
14	00001	\N	2021-09-01 09:00:09	2021-09-01 09:00:21	2021-09-01 09:00:21	\N	\N	\N	\N	\N	\N	\N	\N	t	\N
15	0002	\N	2021-09-01 09:00:15	2021-09-01 09:00:21	2021-09-01 09:00:21	\N	\N	\N	\N	\N	\N	\N	\N	t	\N
\.


--
-- Data for Name: gateways; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.gateways (id, name, description, ip, authentification, created_at, updated_at, deleted_at) FROM stdin;
1	GW01	<p>Gateway 01 vers réseau médor</p>	123.5.6.4/12	Carte à puce	2020-07-13 19:34:45	2020-07-13 19:34:45	\N
2	Workspace One	<p>Test workspace One</p>	10.10.10.1	Token	2021-04-17 21:32:57	2021-04-17 21:40:31	2021-04-17 21:40:31
3	PubicGW	<p>Public Gateway</p>	10.10.10.1	Token	2021-04-17 21:39:04	2021-04-17 21:40:25	2021-04-17 21:40:25
4	PublicGW	<p>Public gateway</p>	8.8.8.8	Token	2021-04-17 21:40:48	2021-04-17 21:48:34	\N
5	GW02	<p>Second gateway</p>	\N	Token	2021-05-18 20:27:13	2021-08-18 20:04:23	\N
\.


--
-- Data for Name: information_process; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.information_process (information_id, process_id) FROM stdin;
3	2
4	3
4	4
4	1
1	4
2	9
5	1
5	2
5	4
5	9
\.


--
-- Data for Name: lans; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.lans (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	LAN_1	Lan principal	2020-07-22 07:42:00	2020-07-22 07:42:00	\N
2	LAN_2	Second LAN	2021-06-23 21:19:38	2021-06-23 21:19:38	\N
3	LAN_0	Lan zero	2021-06-23 21:20:04	2021-06-23 21:20:04	\N
\.


--
-- Data for Name: mans; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.mans (id, name, created_at, updated_at, deleted_at) FROM stdin;
1	MAN_1	2020-08-22 06:17:20	2020-08-22 06:17:20	\N
2	MAN_2	2021-05-07 10:14:27	2021-05-07 10:23:23	\N
3	Test1	2022-04-25 14:43:02	2022-04-25 14:52:49	2022-04-25 14:52:49
4	Test2	2022-04-25 14:43:09	2022-04-25 14:52:49	2022-04-25 14:52:49
\.


--
-- Data for Name: lan_man; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.lan_man (man_id, lan_id) FROM stdin;
1	1
2	1
2	2
2	3
\.


--
-- Data for Name: wans; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.wans (id, name, created_at, updated_at, deleted_at) FROM stdin;
1	WAN01	2021-05-21 12:58:42	2021-05-21 12:58:42	\N
\.


--
-- Data for Name: lan_wan; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.lan_wan (wan_id, lan_id) FROM stdin;
1	1
\.


--
-- Data for Name: logical_server_m_application; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.logical_server_m_application (m_application_id, logical_server_id) FROM stdin;
2	1
2	2
3	2
1	1
18	4
15	3
4	2
4	5
\.


--
-- Data for Name: physical_switches; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.physical_switches (id, name, description, type, created_at, updated_at, deleted_at, site_id, building_id, bay_id) FROM stdin;
1	Switch de test	<p>Master test switch.</p>	Nortel A39	2020-07-17 15:29:09	2022-04-25 18:22:02	\N	1	2	4
2	Switch 2	<p>Description switch 2</p>	Alcatel 430	2020-07-17 15:31:41	2020-07-17 15:31:41	\N	1	1	1
3	Switch 1	<p>Desription du premier switch.</p>	Nortel 2300	2020-07-25 07:27:27	2022-04-25 14:55:06	\N	2	3	5
4	Switch 3	<p>Desciption du switch 3</p>	Alcatel 3500	2020-07-25 09:42:51	2020-07-25 09:43:21	\N	3	5	6
5	AB	<p>Test 2 chars switch</p>	\N	2020-08-22 06:19:45	2020-08-27 18:04:20	2020-08-27 18:04:20	\N	\N	\N
\.


--
-- Data for Name: physical_servers; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.physical_servers (id, name, description, responsible, configuration, created_at, updated_at, deleted_at, site_id, building_id, bay_id, physical_switch_id, type) FROM stdin;
1	Serveur A1	<p>Description du serveur A1</p>	Marc	<p>OS: OS2<br>IP : 127.0.0.1<br>&nbsp;</p>	2020-06-21 07:27:02	2021-11-27 12:12:00	\N	1	2	4	\N	System 840
2	Serveur A2	<p>Description du serveur A2</p>	Marc	<p>Configuration du serveur A<br>OS : Linux 23.4<br>RAM: 32G</p>	2020-06-21 07:27:58	2021-11-27 12:12:12	\N	3	5	6	\N	System 840
3	Serveur A3	<p>Serveur mobile</p>	Marc	<p>None</p>	2020-07-14 17:30:48	2021-11-27 12:12:24	\N	1	1	3	\N	System 840
4	ZZ99	<p>Zoro server</p>	\N	\N	2020-07-14 17:37:50	2020-08-25 16:54:58	2020-08-25 16:54:58	3	5	\N	\N	\N
5	K01	<p>Serveur K01</p>	\N	<p>TOP CPU<br>TOP RAM</p>	2020-07-15 16:37:04	2020-08-29 14:08:09	2020-08-29 14:08:09	1	1	3	\N	\N
6	Mainframe 01	<p>Central accounting system</p>	Marc	<p>40G RAM<br>360P Disk<br>CICS / Cobol</p>	2020-09-05 10:02:49	2021-11-27 12:11:43	\N	1	1	1	2	Type 404
7	Mainframe T1	<p>Mainframe de test</p>	Marc	<p>IDEM prod</p>	2020-09-05 10:22:18	2021-11-27 12:11:01	\N	2	3	4	2	HAL 340
8	Serveur A4	<p>Departmental server</p>	Marc	<p>Standard configuration</p>	2021-06-22 17:34:33	2021-11-27 12:12:50	\N	2	3	5	\N	Mini 900/2
\.


--
-- Data for Name: logical_server_physical_server; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.logical_server_physical_server (logical_server_id, physical_server_id) FROM stdin;
2	1
2	2
1	1
1	7
3	8
4	7
5	8
\.


--
-- Data for Name: m_application_events; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.m_application_events (id, user_id, m_application_id, message, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: m_application_process; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.m_application_process (m_application_id, process_id) FROM stdin;
2	1
2	2
3	2
1	1
14	2
4	3
12	4
16	1
16	2
16	3
16	4
16	9
\.


--
-- Data for Name: man_wan; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.man_wan (wan_id, man_id) FROM stdin;
1	1
\.


--
-- Data for Name: media; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.media (id, model_type, model_id, collection_name, name, file_name, mime_type, disk, size, manipulations, custom_properties, responsive_images, order_column, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: network_switches; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.network_switches (id, name, ip, description, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.oauth_access_tokens (id, user_id, client_id, name, scopes, revoked, created_at, updated_at, expires_at) FROM stdin;
\.


--
-- Data for Name: oauth_auth_codes; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.oauth_auth_codes (id, user_id, client_id, scopes, revoked, expires_at) FROM stdin;
\.


--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.oauth_clients (id, user_id, name, secret, provider, redirect, personal_access_client, password_client, revoked, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: oauth_personal_access_clients; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.oauth_personal_access_clients (id, client_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.oauth_refresh_tokens (id, access_token_id, revoked, expires_at) FROM stdin;
\.


--
-- Data for Name: tasks; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.tasks (id, nom, description, created_at, updated_at, deleted_at) FROM stdin;
1	Tâche 2	Descriptionde la tâche 2	2020-06-13 02:04:07	2020-06-13 02:04:07	\N
2	Tache 1	Description de la tâche 1	2020-06-13 02:04:21	2020-06-13 02:04:21	\N
3	Tâche 3	Description de la tâche 3	2020-06-13 02:04:41	2020-06-13 02:04:41	\N
\.


--
-- Data for Name: operation_task; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.operation_task (operation_id, task_id) FROM stdin;
1	1
1	2
2	1
3	3
4	2
5	1
5	2
5	3
\.


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: peripherals; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.peripherals (id, name, type, description, responsible, created_at, updated_at, deleted_at, site_id, building_id, bay_id) FROM stdin;
1	PER_01	IBM 3400	<p>important peripheral</p>	Marcel	2020-07-25 08:18:40	2020-07-25 08:19:46	\N	1	2	\N
2	PER_02	IBM 5600	<p>Description</p>	Nestor	2020-07-25 08:19:18	2020-07-25 08:19:18	\N	3	5	\N
3	PER_03	HAL 8100	<p>Space device</p>	Niel	2020-07-25 08:19:58	2020-07-25 08:20:18	\N	3	4	\N
\.


--
-- Data for Name: phones; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.phones (id, name, description, type, created_at, updated_at, deleted_at, site_id, building_id, physical_switch_id) FROM stdin;
1	Phone 01	<p>Téléphone de test</p>	MOTOROAL 3110	2020-07-21 07:16:46	2020-07-25 09:15:17	\N	1	1	\N
2	Phone 03	<p>Special AA phone</p>	Top secret red phne	2020-07-21 07:18:01	2020-07-25 09:25:38	\N	2	4	\N
3	Phone 02	<p>Description phone 02</p>	IPhone 2	2020-07-25 08:52:23	2020-07-25 09:25:19	\N	2	3	\N
\.


--
-- Data for Name: physical_routers; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.physical_routers (id, description, type, created_at, updated_at, deleted_at, site_id, building_id, bay_id, name) FROM stdin;
1	<p>Routeur prncipal</p>	Fortinet	2020-07-10 08:58:53	2021-10-12 21:08:21	\N	1	1	1	R1
2	<p>Routeur secondaire</p>	CISCO	2020-07-10 09:19:11	2020-07-25 10:28:17	\N	2	3	5	R2
\.


--
-- Data for Name: vlans; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.vlans (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
1	VLAN_2	VLAN Wifi	2020-07-07 16:31:53	2020-07-07 16:39:10	\N
2	VLAN_1	VLAN publc	2020-07-07 16:34:30	2020-07-07 16:38:53	\N
3	VLAN_3	VLAN application	2020-07-07 16:38:41	2020-07-08 21:35:53	\N
4	VLAN_4	Vlan Client	2020-07-08 21:34:11	2020-07-08 21:36:06	\N
5	VLAN_5	Test Production	2020-07-11 19:12:03	2021-08-18 19:35:54	\N
6	VLAN_6	VLAN démilitarisé	2020-07-11 19:14:55	2021-08-18 19:36:12	\N
7	VLAN_7	Description du VLAN 7	2021-09-07 18:35:28	2021-09-07 18:35:28	\N
8	VLAN_8	Description du VLAN 8	2021-09-07 18:36:20	2021-09-07 18:36:20	\N
\.


--
-- Data for Name: physical_router_vlan; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.physical_router_vlan (physical_router_id, vlan_id) FROM stdin;
1	1
1	3
2	3
\.


--
-- Data for Name: physical_security_devices; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.physical_security_devices (id, name, type, description, created_at, updated_at, deleted_at, site_id, building_id, bay_id) FROM stdin;
1	Magic Gate	Gate	<p>BIG Magic Gate</p>	2021-05-20 16:40:43	2021-11-13 21:29:45	\N	1	1	1
2	Magic Firewall	Firewall	<p>The magic firewall - PT3743</p>	2021-06-07 16:56:26	2021-11-13 21:29:32	\N	2	3	5
3	Sensor-1	Sensor	<p>Temperature sensor</p>	2021-11-13 21:37:14	2021-11-13 21:37:56	\N	1	3	\N
\.


--
-- Data for Name: relations; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.relations (id, importance, name, type, description, created_at, updated_at, deleted_at, source_id, destination_id) FROM stdin;
1	1	Membre	Fourniture de service	<p>Here is the description of this relation</p>	2020-05-21 00:49:47	2021-08-17 10:20:46	\N	1	6
2	2	Membre	Fournisseur de service	<p>Member description</p>	2020-05-21 01:35:11	2021-09-19 13:12:19	\N	2	6
3	1	Fournisseur	Fournisseur de service	<p>description de la relation entre A et le B</p>	2020-05-21 01:39:24	2021-08-17 10:20:59	\N	7	1
4	2	Membre	Fourniture de service	<p>Description du service</p>	2020-05-21 04:23:03	2021-05-23 15:06:05	\N	2	6
5	0	Membre	Fournisseur de service	\N	2020-05-21 04:23:35	2021-05-23 15:05:18	\N	2	6
6	0	Fournisseur	fourniture de service	\N	2020-05-21 04:24:35	2020-05-21 04:24:35	\N	7	2
7	0	Membre	fourniture de service	\N	2020-05-21 04:26:43	2020-05-21 04:26:43	\N	4	6
8	3	Rapporte	\N	\N	2020-05-21 04:32:19	2020-07-05 12:10:01	\N	1	5
9	0	Fournisseur	fourniture de service	\N	2020-05-21 04:33:33	2020-05-21 04:33:33	\N	9	1
10	2	Rapporte	Fournisseur de service	<p>Régelement général APD34</p>	2020-05-22 23:21:02	2020-08-24 16:31:29	\N	1	8
11	2	toto	\N	\N	2020-07-05 12:14:15	2020-07-05 12:14:55	2020-07-05 12:14:55	3	2
12	1	Fournisseur	Fournisseur de service	<p>Analyse de risques</p>	2020-08-24 16:23:30	2020-08-24 16:23:48	\N	2	4
13	1	Fournisseur	Fourniture de service	<p>Description du service</p>	2020-10-14 19:06:24	2021-05-23 15:06:34	\N	2	12
\.


--
-- Data for Name: routers; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.routers (id, name, description, rules, created_at, updated_at, deleted_at, ip_addresses) FROM stdin;
\.


--
-- Data for Name: security_devices; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.security_devices (id, name, description, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: storage_devices; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.storage_devices (id, name, description, created_at, updated_at, deleted_at, site_id, building_id, bay_id, physical_switch_id) FROM stdin;
1	DiskServer 1	<p>Description du serveur d stockage 1</p>	2020-06-21 17:30:16	2020-06-21 17:30:16	\N	1	2	3	\N
2	Oracle Server	<p>Main oracle server</p>	2020-06-21 17:33:51	2020-06-21 17:34:38	\N	1	2	2	\N
\.


--
-- Data for Name: subnetworks; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.subnetworks (id, description, address, ip_allocation_type, responsible_exp, dmz, wifi, name, created_at, updated_at, deleted_at, connected_subnets_id, gateway_id, zone, vlan_id, network_id, default_gateway) FROM stdin;
1	<p>Description du sous-réseau 1</p>	10.10.0.0 /16	Static	Marc	non	non	Subnet1	2020-06-23 14:35:41	2021-11-16 21:24:29	\N	\N	1	ZONE_ACCUEIL	2	1	10.10.0.1
2	<p>Description du subnet 2</p>	10.20.0.0/16	Static	Henri	Oui	Oui	Subnet2	2020-07-04 09:35:10	2021-09-21 16:52:35	\N	\N	5	ZONE_WORK	1	1	10.20.0.1
3	<p>Description du quatrième subnet</p>	10.40.0.0/16	Static	Jean	non	non	Subnet4	2020-11-06 13:56:33	2021-08-20 09:56:50	\N	2	5	ZONE_WORK	4	1	10.40.0.1
4	<p>descrption subnet 3</p>	8.8.8.8 /  255.255.255.0	\N	\N	\N	\N	test subnet 3	2021-02-24 12:49:16	2021-02-24 12:49:33	2021-02-24 12:49:33	\N	\N	\N	\N	\N	\N
5	<p>Troisième sous-réseau</p>	10.30.0.0/16	Static	Jean	non	non	Subnet3	2021-05-19 16:48:39	2021-08-20 09:57:01	\N	\N	1	ZONE_WORK	3	1	10.30.0.1
6	<p>Description du cinquième réseau</p>	10.50.0.0/16	Fixed	Jean	Oui	non	Subnet5	2021-08-17 13:35:28	2021-08-26 17:27:41	\N	\N	1	ZONE_BACKUP	5	1	10.50.0.1
7	<p>Description du sixième sous-réseau</p>	10.60.0.0/16	Fixed	Jean	non	non	Subnet6	2021-08-17 18:32:47	2021-08-26 17:27:57	\N	2	4	ZONE_APP	6	2	10.60.1.1
8	<p>Test</p>	\N	\N	\N	\N	\N	Subnet7	2021-08-18 18:05:50	2021-08-18 18:10:19	2021-08-18 18:10:19	\N	\N	\N	\N	\N	\N
9	<p>Sous-réseau numéro sept</p>	10.70.0.0/16	Static	Jean	Oui	Oui	Subnet7	2021-08-18 18:11:10	2021-08-26 17:27:30	\N	\N	\N	ZONE_BACKUP	5	2	10.70.0.1
10	<p>Sous réseau démilitarisé</p>	10.70.0.0/32	Fixed	Jean	Oui	non	Subnet8	2021-08-18 18:33:48	2021-08-26 17:28:10	\N	\N	1	ZONE_DMZ	7	1	10.70.0.1
11	<p>Description subnet 9</p>	10.90.0.0/32	\N	Jean	non	non	Subnet9	2021-09-07 18:41:02	2021-09-07 18:41:02	\N	\N	\N	ZONE_DATA	8	1	10.90.1.1
\.


--
-- Data for Name: workstations; Type: TABLE DATA; Schema: public; Owner: jfc
--

COPY public.workstations (id, name, description, created_at, updated_at, deleted_at, site_id, building_id, physical_switch_id, type) FROM stdin;
1	Workstation 1	<p>Station de travail compta</p>	2020-06-21 17:09:04	2022-03-20 12:37:13	\N	1	7	\N	ThinThink 460
2	Workstation 2	<p>Station de travail accueil</p>	2020-06-21 17:09:54	2021-10-20 09:14:59	\N	2	3	\N	ThinThink 410
3	Workstation 3	<p>Station de travail back-office</p>	2020-06-21 17:17:57	2021-10-20 09:15:25	\N	2	4	\N	ThinThink 420
\.


--
-- Name: activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.activities_id_seq', 1, false);


--
-- Name: actors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.actors_id_seq', 1, false);


--
-- Name: annuaires_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.annuaires_id_seq', 1, false);


--
-- Name: application_blocks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.application_blocks_id_seq', 1, false);


--
-- Name: application_modules_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.application_modules_id_seq', 1, false);


--
-- Name: application_services_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.application_services_id_seq', 1, false);


--
-- Name: audit_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.audit_logs_id_seq', 1, false);


--
-- Name: bays_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.bays_id_seq', 1, false);


--
-- Name: buildings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.buildings_id_seq', 1, false);


--
-- Name: cartographer_m_application_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.cartographer_m_application_id_seq', 1, false);


--
-- Name: certificates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.certificates_id_seq', 1, false);


--
-- Name: databases_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.databases_id_seq', 1, false);


--
-- Name: dhcp_servers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.dhcp_servers_id_seq', 1, false);


--
-- Name: dnsservers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.dnsservers_id_seq', 1, false);


--
-- Name: domaine_ads_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.domaine_ads_id_seq', 1, false);


--
-- Name: entities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.entities_id_seq', 1, false);


--
-- Name: external_connected_entities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.external_connected_entities_id_seq', 1, false);


--
-- Name: fluxes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.fluxes_id_seq', 1, false);


--
-- Name: forest_ads_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.forest_ads_id_seq', 1, false);


--
-- Name: gateways_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.gateways_id_seq', 1, false);


--
-- Name: information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.information_id_seq', 1, false);


--
-- Name: lans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.lans_id_seq', 1, false);


--
-- Name: logical_servers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.logical_servers_id_seq', 1, false);


--
-- Name: m_application_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.m_application_events_id_seq', 1, false);


--
-- Name: m_applications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.m_applications_id_seq', 1, false);


--
-- Name: macro_processuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.macro_processuses_id_seq', 1, false);


--
-- Name: mans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.mans_id_seq', 1, false);


--
-- Name: media_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.media_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.migrations_id_seq', 150, true);


--
-- Name: network_switches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.network_switches_id_seq', 1, false);


--
-- Name: networks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.networks_id_seq', 1, false);


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.oauth_clients_id_seq', 1, false);


--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.oauth_personal_access_clients_id_seq', 1, false);


--
-- Name: operations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.operations_id_seq', 1, false);


--
-- Name: peripherals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.peripherals_id_seq', 1, false);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);


--
-- Name: phones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.phones_id_seq', 1, false);


--
-- Name: physical_routers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.physical_routers_id_seq', 1, false);


--
-- Name: physical_security_devices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.physical_security_devices_id_seq', 1, false);


--
-- Name: physical_servers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.physical_servers_id_seq', 1, false);


--
-- Name: physical_switches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.physical_switches_id_seq', 1, false);


--
-- Name: processes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.processes_id_seq', 1, false);


--
-- Name: relations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.relations_id_seq', 1, false);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.roles_id_seq', 1, false);


--
-- Name: routers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.routers_id_seq', 1, false);


--
-- Name: security_devices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.security_devices_id_seq', 1, false);


--
-- Name: sites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.sites_id_seq', 1, false);


--
-- Name: storage_devices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.storage_devices_id_seq', 1, false);


--
-- Name: subnetworks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.subnetworks_id_seq', 1, false);


--
-- Name: tasks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.tasks_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: vlans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.vlans_id_seq', 1, false);


--
-- Name: wans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.wans_id_seq', 1, false);


--
-- Name: wifi_terminals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.wifi_terminals_id_seq', 1, false);


--
-- Name: workstations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.workstations_id_seq', 1, false);


--
-- Name: zone_admins_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jfc
--

SELECT pg_catalog.setval('public.zone_admins_id_seq', 1, false);


--
-- PostgreSQL database dump complete
--

