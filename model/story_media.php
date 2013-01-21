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
require_once 'model/media_item.php';

class story_media extends fs_model
{
   public $story_id;
   public $media_id;
   
   public function __construct($i = FALSE)
   {
      parent::__construct('story_medias');
      if($i)
      {
         $this->id = $i['_id'];
         $this->story_id = $i['story_id'];
         $this->media_id = $i['media_id'];
      }
      else
      {
         $this->id = NULL;
         $this->story_id = NULL;
         $this->media_id = NULL;
      }
   }
   
   public function media_item()
   {
      $media_item = new media_item();
      return $media_item->get($this->media_id);
   }
   
   public function get($id)
   {
      $data = $this->collection->findone( array('_id' => new MongoId($id)) );
      if($data)
         return new story_media($data);
      else
         return FALSE;
   }
   
   public function exists()
   {
      if( is_null($this->id) )
         return FALSE;
      else
      {
         $data = $this->collection->findone( array('_id' => new MongoId($this->id)) );
         if($data)
            return TRUE;
         else
            return FALSE;
      }
   }
   
   public function save()
   {
      $this->story_id = $this->var2str($this->story_id);
      $this->media_id = $this->var2str($this->media_id);
      
      $data = array(
          'story_id' => $this->story_id,
          'media_id' => $this->media_id
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
      $ilist = array();
      foreach($this->collection->find() as $i)
         $ilist[] = new story_media($i);
      return $ilist;
   }
   
   public function all4story($sid)
   {
      $ilist = array();
      foreach($this->collection->find( array('story_id' => $this->var2str($sid)) ) as $i)
         $ilist[] = new story_media($i);
      return $ilist;
   }
   
   public function all4media($mid)
   {
      $ilist = array();
      foreach($this->collection->find( array('media_id' => $this->var2str($mid)) ) as $i)
         $ilist[] = new story_media($i);
      return $ilist;
   }
}

?>