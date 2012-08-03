BotenInschrijfSysteem (BIS) v1.1.0

2008-2012 Erik Roos, KGR de Hunze
Support: bis@hunze.nl

Available under the BSD license

Starting instructions (in Dutch):
 * MySQL-database inrichten m.b.v. res/BIS_datamodel.sql
 * example.bis.conf is het configuratiebestand voor BIS, met o.a. de connectie-
gegevens voor de database
 * Regels met een # zijn commentaar en worden niet bekeken
 * Vul dit bestand goed in, hernoem het naar bis.conf en zet het in de rootdir van BIS
 * LET OP: zorg dat de rest van de wereld geen leesrechten heeft op dit bestand
zodra het online staat!
 * Zet een cronjob op die elke nacht de scripts daily_migration.php en daily_mail.php draait 
(in die volgorde)

Major TODO's:
 * Translate into English and other languages, add translation support
 * Set up proper classes and MVC structure