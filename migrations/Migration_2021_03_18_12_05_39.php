<?php
declare(strict_types=1);

namespace migrations;

use papi\Migrations\Migration;

class Migration_2021_03_18_12_05_39  implements Migration
{
    public function getSQL(): array
    {
        return array (
  0 => 'create table comment(id integer generated always as identity primary key, post_id INT)',
  1 => 'create table post(id integer generated always as identity primary key, content text)',
  2 => 'alter table comment add constraint comment_post_id_fkey foreign key (post_id) REFERENCES post(id) on delete cascade on update cascade',
  3 => 'create unique index FKU_comment_post on comment(post_id)',
);
    }

    public function getMapping(): array
    {
        return array (
  'tables' => 
  array (
    'comment' => 
    array (
      'id' => 'integer generated always as identity primary key',
      'post_id' => 'INT',
    ),
    'post' => 
    array (
      'id' => 'integer generated always as identity primary key',
      'content' => 'text',
    ),
  ),
  'foreign_keys' => 
  array (
    'comment' => 
    array (
      'post_id' => 'REFERENCES post(id) on delete cascade on update cascade',
    ),
  ),
  'indexes' => 
  array (
    0 => 'unique index FKU_comment_post on comment(post_id)',
  ),
);
    }
}
