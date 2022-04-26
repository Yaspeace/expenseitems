--
-- PostgreSQL database dump
--

-- Dumped from database version 14.0
-- Dumped by pg_dump version 14.0

-- Started on 2022-04-26 03:32:43

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 209 (class 1259 OID 65605)
-- Name: periods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.periods (
    id integer NOT NULL,
    name text NOT NULL
);


--
-- TOC entry 211 (class 1259 OID 81976)
-- Name: rules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rules (
    id integer NOT NULL,
    counterparty_id text,
    project_id text,
    comment text,
    purpose text,
    expenseitem_id text NOT NULL,
    uid text NOT NULL,
    number integer NOT NULL,
    operand1 text,
    operand2 text,
    operand3 text
);


--
-- TOC entry 210 (class 1259 OID 81975)
-- Name: rules1_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rules1_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3323 (class 0 OID 0)
-- Dependencies: 210
-- Name: rules1_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rules1_id_seq OWNED BY public.rules.id;


--
-- TOC entry 212 (class 1259 OID 90159)
-- Name: user_periods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_periods (
    uid text NOT NULL,
    period_id integer NOT NULL,
    previous_start text,
    next_start text
);


--
-- TOC entry 3172 (class 2604 OID 81979)
-- Name: rules id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rules ALTER COLUMN id SET DEFAULT nextval('public.rules1_id_seq'::regclass);


--
-- TOC entry 3174 (class 2606 OID 65611)
-- Name: periods periods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.periods
    ADD CONSTRAINT periods_pkey PRIMARY KEY (id);


--
-- TOC entry 3176 (class 2606 OID 81983)
-- Name: rules rules1_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rules
    ADD CONSTRAINT rules1_pkey PRIMARY KEY (id);


--
-- TOC entry 3178 (class 2606 OID 90165)
-- Name: user_periods user_periods_pkey1; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_periods
    ADD CONSTRAINT user_periods_pkey1 PRIMARY KEY (uid);


-- Completed on 2022-04-26 03:32:44

--
-- PostgreSQL database dump complete
--

