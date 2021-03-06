<?php
/*
 * This file is part of FeedStorm
 * Copyright (C) 2013  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'base/fs_model.php';
require_once 'model/suscription.php';
require_once 'model/feed_story.php';

class visitor extends fs_model
{
   public $nick;
   public $user_agent;
   public $last_login_date;
   
   public $noob;
   private $suscriptions;
   
   public function __construct($k=FALSE)
   {
      parent::__construct('visitors');
      if( $k )
      {
         $this->id = $k['_id'];
         $this->nick = $k['nick'];
         $this->user_agent = $k['user_agent'];
         $this->last_login_date = $k['last_login_date'];
         $this->noob = FALSE;
      }
      else
      {
         $this->id = NULL;
         $this->nick = $this->random_string(15);
         $this->login();
         $this->noob = TRUE;
      }
   }
   
   public function login_date()
   {
      return Date('Y-m-d H:m', $this->last_login_date);
   }
   
   public function login_timesince()
   {
      return $this->time2timesince($this->last_login_date);
   }
   
   public function mobile()
   {
      return (strstr(strtolower($this->user_agent), 'mobile') || strstr(strtolower($this->user_agent), 'android'));
   }
   
   public function human()
   {
      return !(strstr(strtolower($this->user_agent), 'bot') OR strstr(strtolower($this->user_agent), 'spider'));
   }
   
   public function login()
   {
      if( isset($_SERVER['HTTP_USER_AGENT']) )
         $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
      else
         $this->user_agent = 'unknown';
      
      $this->last_login_date = time();
   }
   
   public function suscriptions()
   {
      if( !isset($this->suscriptions) )
      {
         $suscription = new suscription();
         $this->suscriptions = $suscription->all4visitor($this->id);
      }
      return $this->suscriptions;
   }
   
   public function last_stories()
   {
      $fids = array();
      foreach($this->suscriptions() as $sus)
         $fids[] = $sus->feed_id;
      $feed_story = new feed_story();
      $stories = array();
      foreach($feed_story->last4feeds($fids) as $fs)
         $stories[] = $fs->story();
      return $stories;
   }
   
   public function get($id)
   {
      $data = $this->collection->findone( array('_id' => new MongoId($id)) );
      if($data)
         return new visitor($data);
      else
         return FALSE;
   }
   
   public function exists()
   {
      if( is_null($this->id) )
         return FALSE;
      else
      {
         $data = $this->collection->findone( array('_id' => $this->id) );
         if($data)
            return TRUE;
         else
            return FALSE;
      }
   }
   
   public function save()
   {
      $data = array(
          'nick' => $this->nick,
          'user_agent' => $this->user_agent,
          'last_login_date' => $this->last_login_date
      );
      
      if( $this->exists() )
      {
         $filter = array('_id' => $this->id);
         $this->collection->update($filter, $data);
      }
      else
      {
         $this->collection->insert($data);
         $this->id = $data['_id'];
      }
   }
   
   public function delete()
   {
      $this->collection->remove( array('_id' => $this->id) );
   }
   
   public function all()
   {
      $vlist = array();
      foreach($this->collection->find() as $v)
         $vlist[] = new visitor($v);
      return $vlist;
   }
}

?>