<?php

$DB_SERVER = getenv("MVC_SERVER") ?: "192.168.10.16";
$DB_DATABASE = getenv("MVC_DB") ?: "ap3_dannysashakarim";
$DB_USER = getenv("MVC_USER") ?: "ap3_dannysashakarim-2";
$DB_PASSWORD = getenv("MVC_TOKEN") ?: "egyDTYYS";
$DEBUG = getenv("MVC_DEBUG") ?: true;
$URL_BASE = getenv("URL_BASE") ?: "http://localhost:9000/";
$MAIL_SERVER = getenv("MVC_MAIL_SERVER") ?: "mail.dombtsig.local";
$FROM_EMAIL = getenv("MVC_FROM_EMAIL") ?: "contact@localhost.fr";

return array(
    "DB_USER" => $DB_USER,
    "DB_PASSWORD" => $DB_PASSWORD,
    // Pour MySQL, utilisez la ligne suivante :
    "DB_DSN" => "mysql:host=$DB_SERVER;dbname=$DB_DATABASE;charset=utf8",
    // Pour du SQLite, utilisez la ligne suivante :
    // "DB_DSN" => "sqlite:./data/database.db",
    "DEBUG" => $DEBUG,
    "MAIL_SERVER" => $MAIL_SERVER,
    "FROM_EMAIL" => $FROM_EMAIL,
    "URL_BASE" => $URL_BASE
);
