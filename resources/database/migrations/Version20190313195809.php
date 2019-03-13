<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313195809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->createOAuthTables();
    }

    public function postUp(Schema $schema) : void
    {
        $this->connection->executeQuery("INSERT INTO oauth_clients (client_id, redirect_uri) VALUES ('Micro', 'http://redirect.to');");
    }

    public function down(Schema $schema) : void
    {
        $this->dropOAuthTables();
    }

    private function createOAuthTables()
    {
        $this->addSql('CREATE TABLE oauth_clients (client_id VARCHAR(80) NOT NULL, client_secret VARCHAR(80), redirect_uri VARCHAR(2000) NOT NULL, grant_types VARCHAR(80), scope VARCHAR(100), user_id VARCHAR(80), CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id));');
        $this->addSql('CREATE TABLE oauth_access_tokens (access_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT access_token_pk PRIMARY KEY (access_token));');
        $this->addSql('CREATE TABLE oauth_authorization_codes (authorization_code VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), redirect_uri VARCHAR(2000), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));');
        $this->addSql('CREATE TABLE oauth_refresh_tokens (refresh_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));');
        $this->addSql('CREATE TABLE oauth_users (username VARCHAR(255) NOT NULL, password VARCHAR(2000), first_name VARCHAR(255), last_name VARCHAR(255), CONSTRAINT username_pk PRIMARY KEY (username));');
        $this->addSql('CREATE TABLE oauth_scopes (scope TEXT, is_default BOOLEAN);');
        $this->addSql('CREATE TABLE oauth_jwt (client_id VARCHAR(80) NOT NULL, subject VARCHAR(80), public_key VARCHAR(2000), CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id));');
    }

    private function dropOAuthTables()
    {
        $this->addSql('DROP TABLE `oauth_clients`');
        $this->addSql('DROP TABLE `oauth_access_tokens`');
        $this->addSql('DROP TABLE `oauth_authorization_codes`');
        $this->addSql('DROP TABLE `oauth_refresh_tokens`');
        $this->addSql('DROP TABLE `oauth_users`');
        $this->addSql('DROP TABLE `oauth_scopes`');
        $this->addSql('DROP TABLE `oauth_jwt`');
    }
}
