<?php
pdo_query("
DROP TABLE IF EXISTS " . tablename('d1sj_card_bank') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_card') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_cash') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_group') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_lend') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_logs') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_news') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_order') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_record') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_setting') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_user') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_category') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_newclass') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_banner') . ";
DROP TABLE IF EXISTS " . tablename('d1sj_card_loan') . ";

");