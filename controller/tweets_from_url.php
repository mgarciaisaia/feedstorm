<?php
/*
 * This file is part of FeedStorm
 * Copyright (C) 2012  Carlos Garcia Gomez  neorazorx@gmail.com
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

class tweets_from_url extends fs_controller
{
   public $full_page;
   public $story;
   public $story_link_urlized;
   public $tweets;
   
   public function __construct()
   {
      parent::__construct('tweets_from_url', 'Tweets...', 'tweets_from_url');
   }
   
   protected function process()
   {
      $this->story = FALSE;
      $this->tweets = array();
      
      $this->full_page = !isset($_POST['no_full_page']);
      
      if( isset($_GET['url']) )
      {
         $url = urldecode($_GET['url']);
         if( isset($_GET['feed']) )
         {
            $feed = new feed();
            $feed = $feed->get( urldecode($_GET['feed']) );
            if( $feed )
            {
               $this->story = $feed->get_story_by_url($url);
               if( $this->story )
               {
                  $this->story_link_urlized = urlencode( $this->story->link );
                  $this->tweets = $this->tweet->all_from_url( $this->story->link );
                  $this->visitor->add2log($this->story->title);
               }
            }
         }
      }
   }
   
   public function get_description()
   {
      if( $this->story )
         return $this->story->description;
      else
         parent::get_description();
   }
   
   public function url()
   {
      if( $this->story )
         return $this->story->url();
      else
         return parent::url();
   }
}

?>
