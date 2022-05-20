### What, how & why:
Papi handles database schema via migrations. The way it works is that there are two db schemas - the state of actual database and the state of PHP coded resource classes. If there are any differences - an SQL migration with statements to bring the Postgresql database up to date can be created. After creating it - you can execute it via command line. Migrations are stored in /migrations directory of the project.

### Commands:
* **migration:make** Generates migrations based on differences between code (PHP Resource objects) and current database schema
* **migration:execute** Executes created migrations

### Sample migration file:
```
class Migration_2021_03_22_04_37_27 implements Migration
{
    public function getSQL(): array
    {
        return [
            0 => 'create table comment(id integer generated always as identity primary key)',
            1 => 'create table comment_post(id integer generated always as identity primary key, comment_id INT NOT NULL, post_id INT NOT NULL)',
            2 => 'create table users(id integer generated always as identity primary key, username varchar(30) unique, roles varchar(100), password varchar(110))',
            3 => 'create table post(id integer generated always as identity primary key, content text)',
            4 => 'alter table comment_post add constraint comment_post_comment_id_fkey foreign key (comment_id) REFERENCES comment(id) on delete cascade on update cascade',
            5 => 'alter table comment_post add constraint comment_post_post_id_fkey foreign key (post_id) REFERENCES post(id) on delete cascade on update cascade',
            6 => 'create index FK_comment_post_comment ON comment_post(comment_id)',
            7 => 'create index FK_comment_post_post ON comment_post(post_id)',
        ];
    }

    public function getMapping(): array
    {
        return [
            'tables'       =>
                [
                    'comment'      =>
                        [
                            'id' => 'integer generated always as identity primary key',
                        ],
                    'comment_post' =>
                        [
                            'id'         => 'integer generated always as identity primary key',
                            'comment_id' => 'INT NOT NULL',
                            'post_id'    => 'INT NOT NULL',
                        ],
                    'users'        =>
                        [
                            'id'       => 'integer generated always as identity primary key',
                            'username' => 'varchar(30) unique',
                            'roles'    => 'varchar(100)',
                            'password' => 'varchar(110)',
                        ],
                    'post'         =>
                        [
                            'id'      => 'integer generated always as identity primary key',
                            'content' => 'text',
                        ],
                ],
            'foreign_keys' =>
                [
                    'comment_post' =>
                        [
                            'comment_id' => 'REFERENCES comment(id) on delete cascade on update cascade',
                            'post_id'    => 'REFERENCES post(id) on delete cascade on update cascade',
                        ],
                ],
            'indexes'      =>
                [
                    0 => 'index FK_comment_post_comment ON comment_post(comment_id)',
                    1 => 'index FK_comment_post_post ON comment_post(post_id)',
                ],
        ];
    }
}
```