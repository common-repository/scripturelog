<?php
/**
 * @package ScriptureLog
 * @author J. Max Wilson
 * @version 1.1.1
 */
/*
Plugin Name: ScriptureLog
Plugin URI: http://scripturelog.com
Description: An Open Source plugin that turns Wordpress into a platform for collaborative LDS scripture study
Author: J. Max Wilson
Version: 1.1.1
Author URI: http://www.sixteensmallstones.org
*/
/*  Copyright 2009  J. Max Wilson and Daniel Bartholomew

   Redistribution and use in source and binary forms, with or without 
	 modification, are permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, 
	    this list of conditions and the following disclaimer.
   2. Redistributions in binary form must reproduce the above copyright notice, 
	    this list of conditions and the following disclaimer in the documentation 
			and/or other materials provided with the distribution.
   3. The name of the author may not be used to endorse or promote products 
	    derived from this software without specific prior written permission.

   THIS SOFTWARE IS PROVIDED BY THE AUTHOR "AS IS" AND ANY EXPRESS OR IMPLIED
	 WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF 
	 MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO 
	 EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
	 SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
	 PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
	 OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
	 WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR 
	 OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF 
	 ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
if (!class_exists("ScriptureLog"))
{
	class ScriptureLog
	{
		var $VERSION = '1.1.1';
		
		function ScriptureLog()
		{
		}
		
		function activate()
		{
			add_option('scripturelog_version', $this->VERSION);
			$this->applyDatabaseChanges();
		}
		
		function uninstall()
		{
			global $wpdb;
			
			$strSql = "DELETE FROM ".$wpdb->posts." WHERE scripturelog <> 0;";
			$wpdb->query($strSql);
			$strSql = "ALTER TABLE ".$wpdb->posts." DROP scripturelog;";
			$wpdb->query($strSql);
			
			delete_option('scripturelog_version');
		}
		
		function settingsMenu()
		{
			add_menu_page('ScriptureLog Settings', 'ScriptureLog', 8, __FILE__, array(&$this, 'generalSettings'));
			add_submenu_page(__FILE__,'ScriptureLog - Install / Uninstall Volumes','Manage',8, __FILE__, array(&$this, 'generalSettings'));
			if (get_option('scripturelog_book_of_mormon'))
			{
				//add_submenu_page(__FILE__,'ScriptureLog - Book of Mormon Settings','Book of Mormon',8, 'bookofMormonSettings', array(&$this, 'bookofMormonSettings'));
			} 
		}
		
		function generalSettings()
		{
			echo '<div class="wrap">';
			echo '<h2>ScriptureLog - Install / Uninstall Volumes</h2>';
			if( $_POST['install_book_of_mormon'] == 'Install' )
			{
				$this->installVolume('The Book of Mormon', 'scripturelog_book_of_mormon', 'book_of_mormon.sql');
			}
			if( $_POST['uninstall_book_of_mormon'] == 'Uninstall' )
			{
				$this->uninstallVolume('The Book of Mormon', 3, 'scripturelog_book_of_mormon');
			}
			if( $_POST['install_new_testament'] == 'Install' )
			{
				$this->installVolume('The New Testament', 'scripturelog_new_testament', 'new_testament.sql');
			}
			if( $_POST['uninstall_new_testament'] == 'Uninstall' )
			{
				$this->uninstallVolume('The New Testament', 2, 'scripturelog_new_testament');
			}
			if( $_POST['install_old_testament'] == 'Install' )
			{
				$this->installVolume('The Old Testament', 'scripturelog_old_testament', 'old_testament.sql');
			}
			if( $_POST['uninstall_old_testament'] == 'Uninstall' )
			{
				$this->uninstallVolume('The Old Testament', 1, 'scripturelog_old_testament');
			}
			if( $_POST['install_doctrine_covenants'] == 'Install' )
			{
				$this->installVolume('Doctrine and Covenants', 'scripturelog_doctrine_covenants', 'doctrine_and_covenants.sql');
			}
			if( $_POST['uninstall_doctrine_covenants'] == 'Uninstall' )
			{
				$this->uninstallVolume('Doctrine and Covenants', 4, 'scripturelog_doctrine_covenants');
			}
			if( $_POST['install_pearl_of_great_price'] == 'Install' )
			{
				$this->installVolume('The Pearl of Great Price', 'scripturelog_pearl_of_great_price', 'pearl_of_great_price.sql');
			}
			if( $_POST['uninstall_pearl_of_great_price'] == 'Uninstall' )
			{
				$this->uninstallVolume('The Pearl of Great Price', 5, 'scripturelog_pearl_of_great_price');
			}
			echo '<div style="width: 50%; float: right;"><h3>Important Information</h3><ul style="list-style-type: disc;">
			      <li><strong>INSTALLATION</strong> - Installing a volume of scripture will add 
				  <em>thousands of hierarchical, inter-linking pages</em> to your blog. We recommend that you only use this plugin
				  with a new blog set up especially for the purpose of scripture study and not with an established blog with many existing
				  posts and pages. When you install a volume your database will also be altered slightly so that these pages can be easily identified
				  and filtered out of your normal pages list as well as removed if you uninstall the plugin. <em>Please back up your database 
				  before installing or uninstalling volumes.</em></li>
				  <li><strong>UNINSTALLATION</strong> - Uninstalling a volume deletes all of the pages associated to that volume from the database.
				  It does not remove any comments that may have been made on those pages.  So if you reinstall the volume, all of the comments
				  previously made on the pages will still be there. You will probably want to uninstall volumes before you upgrade or deactive 
				  the ScriptureLog plugin and then reinstall them once the plugin has completed upgrading or been reactivated.  <em>Please back up
				  your database before installing or uninstalling volumes.</em></li>
				  <li><strong>DEACTIVATION</strong> - If you deactivate the ScriptureLog plugin without first uninstalling the volumes the pages will 
				  appear in your normal pages list and it could cause very serious problems with your layout and your dashboard performance. Uninstall
				  all of the volumes before deactivating the plugin.</li>
				  <li><strong>COMMENTS</strong> - Some themes, like the default WordPress theme, display the comment form on posts but not on pages. 
				  To enable comments on your ScriptureLog, you may have to modify the portion of your theme that 
				  displays pages to include the comment form (page.php) by adding
				  <pre style="margin: 10px; padding: 5px; background-color: white;">&lt;?php comments_template(); ?&gt;</pre>  
				  Then disable comments on any pages where you do not want to allow comments by editing the page and uncheckmarking 
				  "Allow comments on this post" in the Discussion options.  Alternatively, if you want to enable comments on your 
				  ScriptureLog but not on your other pages use 
				  <pre style="margin: 10px; padding: 5px; background-color: white;">&lt;?php if ($post->scripturelog) {comments_template();} ?&gt;</pre>
				  in your template file instead of the code above.</li>
				  </ul></div>';
			echo '<form method="post" action="">';
			wp_nonce_field('update-options');
			echo '<h3>Volumes</h3>';
			echo '<table class="form-table" style="clear: none; width: 50%;">';
			
			echo '<tr valign="top">';
			if (get_option('scripturelog_book_of_mormon') == 0)
			{
				echo '<th scope="row">The Book of Mormon</th>';
				echo '<td>';
				echo '<input type="submit" name="install_book_of_mormon" value="Install" />';
			}
			else
			{
				echo '<th scope="row"><strong>The Book of Mormon</strong></th>';
				echo '<td>';
				echo '<input type="submit" name="uninstall_book_of_mormon" value="Uninstall" />';
			}
			echo '</td></tr>';
			
			echo '<tr valign="top">';
			if (get_option('scripturelog_old_testament') == 0)
			{
				echo '<th scope="row">The Old Testament</th>';
				echo '<td>';
				echo '<input type="submit" name="install_old_testament" value="Install" />';
			}
			else
			{
				echo '<th scope="row"><strong>The Old Testament</strong></th>';
				echo '<td>';
				echo '<input type="submit" name="uninstall_old_testament" value="Uninstall" />';
			}
			echo '</td></tr>';
			
			echo '<tr valign="top">';
			if (get_option('scripturelog_new_testament') == 0)
			{
				echo '<th scope="row">The New Testament</th>';
				echo '<td>';
				echo '<input type="submit" name="install_new_testament" value="Not Available Yet" disabled="disabled" />';
			}
			else
			{
				echo '<th scope="row"><strong>The New Testament</strong></th>';
				echo '<td>';
				echo '<input type="submit" name="uninstall_new_testament" value="Uninstall" />';
			}
			echo '</td></tr>';
			
			echo '<tr valign="top">';
			if (get_option('scripturelog_doctrine_covenants') == 0)
			{
				echo '<th scope="row">Doctrine and Covenants</th>';
				echo '<td>';
				echo '<input type="submit" name="install_doctrine_covenants" value="Not Available Yet" disabled="disabled" />';
			}
			else
			{
				echo '<th scope="row"><strong>Doctrine and Covenants</strong></th>';
				echo '<td>';
				echo '<input type="submit" name="uninstall_doctrine_covenants" value="Uninstall" />';
			}
			echo '</td></tr>';
			
			echo '<tr valign="top">';
			if (get_option('scripturelog_pearl_of_great_price') == 0)
			{
				echo '<th scope="row">The Pearl of Great Price</th>';
				echo '<td>';
				echo '<input type="submit" name="install_pearl_of_great_price" value="Not Available Yet" disabled="disabled" />';
			}
			else
			{
				echo '<th scope="row"><strong>The Pearl of Great Price</strong></th>';
				echo '<td>';
				echo '<input type="submit" name="uninstall_pearl_of_great_price" value="Uninstall" />';
			}
			echo '</td></tr>';
			
			echo '</table></form>';

		}
		
		function bookOfMormonSettings()
		{
			echo '<div class="wrap">';
			echo '<h2>ScriptureLog - Book of Mormon Settings</h2>';
		}
		
		function applyDatabaseChanges()
		{
			set_time_limit(600);
			global $wpdb;
			
			$strSql = "DESC ".$wpdb->posts.";";
			$columns = $wpdb->get_results($strSql);
			$boolHasFlag = false;
			$boolAllowNull = true;
			$boolHasNull = false;
			$boolHasIndex = false;
			foreach($columns as $column)
			{
				if ($column->Field == 'scripturelog')
				{
					$boolHasFlag = true;
					
					if (strtolower($column->null) == 'no')
					{
						$boolAllowNull = false;
					}
					else
					{		
						$strSql = "SELECT COUNT(1) FROM ".$wpdb->posts." WHERE scripturelog IS NULL;";
						$intNullRows = $wpdb->get_var($wpdb->prepare($strSql));
						if ($intNullRows > 0)
						{
							$boolHasNull = true;
						}
					}
					
					if (strtolower($column->key) == 'mul')
					{
						$boolHasIndex = true;
					}
				}
			}
			if (!$boolHasFlag)
			{
				$strSql = "ALTER TABLE ".$wpdb->posts." ADD scripturelog TINYINT UNSIGNED DEFAULT 0;";
				$wpdb->query($strSql);
				$strSql = null;
				unset($strSql);
			}
			if ($boolHasNull)
			{
				$strSql = "UPDATE ".$wpdb->posts." SET scripturelog = 0 WHERE scripturelog IS NULL;";
				$wpdb->query($strSql);
				$strSql = null;
				unset($strSql);
			}
			if ($boolAllowNull)
			{
				$strSql = "ALTER TABLE ".$wpdb->posts." MODIFY scripturelog TINYINT(3) UNSIGNED NOT NULL DEFAULT 0;";
				$wpdb->query($strSql);
				$strSql = null;
				unset($strSql);
			}
			if (!$boolHasIndex)
			{
				$strSql = "ALTER TABLE ".$wpdb->posts." ADD INDEX idx_scripturelog (scripturelog);";
				$wpdb->query($strSql);
				$strSql = null;
				unset($strSql);
			}
		}
		
		function installVolume($strVolume, $strOptionName, $strVolumeFile)
		{
			global $wpdb;
			$url = get_bloginfo('url');
			
			echo '<div class="updated"><p><strong>';

			echo 'Installed ' .$strVolume . "<br/>";
			flush();
			ob_flush();
			set_time_limit(600);
			 
			$stepStart = microtime(true);
			$fp = fopen(dirname(__file__) . '/install/' . $strVolumeFile, "r");
			$intCount = 0;
			while(!feof($fp))
			{
				$strSql = fgets($fp, 30000);
				$strSql = trim($strSql);
				if ($strSql)
				{
					$wpdb->query($strSql);
					$intCount++;
				}
			}
			fclose($fp);
			$stepEnd = microtime(true);
			echo 'Inserted '.$intCount.' Pages ('. ($stepEnd - $stepStart) . " seconds)";
			
			add_option($strOptionName, true);
			
			echo '</strong></p></div>';
		}
		
		function uninstallVolume($strVolume, $intVolume, $strOptionName)
		{
			global $wpdb;
			
			$stepStart = microtime(true);
			$strSql = "DELETE FROM ".$wpdb->posts." WHERE post_type = 'page' AND scripturelog = ".$intVolume.";";
			$intCount = $wpdb->query($strSql);
			$stepEnd = microtime(true);
			
			delete_option($strOptionName);
			
			echo '<div class="updated"><p><strong>';
			echo 'Uninstalled ' . $strVolume . "<br/>";
			echo 'Removed '.$intCount.' Pages ('. ($stepEnd - $stepStart) . " seconds)";
			echo '</strong></p></div>';
		}
		
		function installTags()
		{
			//echo "Loading Words...";
			flush();
			$stepStart = microtime(true);
			$fp = fopen(dirname(__file__) . '/install/terms.sql', "r");
			if ($fp)
			{
				while (!feof($fp))
				{
					$strSql = fgets($fp);
					$wpdb->query(str_replace('wp_terms',$wpdb->terms,$strSql));
				}
			}
			fclose($fp);
			$stepEnd = microtime(true);
			//echo ($stepEnd - $stepStart) . " seconds<br />";
			
			//echo "Assigning Word Taxonomy...";
			flush();
			$stepStart = microtime(true);
			$fp = fopen(dirname(__file__) . '/install/term_taxonomy.sql', "r");
			if ($fp)
			{
				while (!feof($fp))
				{
					$strSql = fgets($fp);
					$wpdb->query(str_replace('wp_term_taxonomy',$wpdb->term_taxonomy,$strSql));
				}
			}
			fclose($fp);
			$stepEnd = microtime(true);
			//echo ($stepEnd - $stepStart) . " seconds<br />";
			
			//echo "Building Word -> Page Relationships...";
			flush();
			$stepStart = microtime(true);
			$fp = fopen(dirname(__file__) . '/install/term_relationships.sql', "r");
			if ($fp)
			{
				while (!feof($fp))
				{
					$strSql = fgets($fp);
					$wpdb->query(str_replace('wp_term_relationships',$wpdb->term_relationships,$strSql));
				}
			}
			fclose($fp);
			$stepEnd = microtime(true);
			//echo ($stepEnd - $stepStart) . " seconds<br />";
			
			update_option('scripturelog_b_o_m_tags', 'yes');
		}
		
		function addTagFormToPages()
		{
			global $post;
			if ($post->scripturelog)
			{
				$strTags = get_tags_to_edit($post->ID);
				print '
				<fieldset id="tagdiv">
				<legend>'._e("Tags (separate multiple tags with commas: cats, pet food, dogs)").'</legend>
				<div><input type="text" name="tags_input" class="tags-input" id="tags-input" size="30" tabindex="3" value="'.$strTags.'" /></div>
				</fieldset><p>&nbsp;</p>';
			}
		}
		
		function includePagesInWhereClause($strWhere)
		{
			if(is_tag())
			{
	    		$strWhere = preg_replace("/ ([0-9a-zA-Z_]*\.?)post_type = 'post'/","(${1}post_type = 'post' OR (${1}post_type = 'page' AND scripturelog <> 0))", $strWhere );
	   		}
	
	  		return $strWhere;
		}
		
		function initWidget()
		{
			if (!function_exists('register_sidebar_widget'))
			{
				return;
			}
				register_sidebar_widget('ScriptureLog Widget','displayScriptureLogWidget');
		}
		
		function excludeScriptureLogPages($query)
		{
			global $wpdb;
			
			if (stripos($query, "WHERE (post_type = 'page' AND post_status = 'publish')") !== false)
			{
				$query = str_replace("WHERE (post_type = 'page' AND post_status = 'publish')",
									 "WHERE (post_type = 'page' AND post_status = 'publish' AND scripturelog = 0)",
									$query);
			}
			
			if (stripos($query, "WHERE 1=1  AND ".$wpdb->posts.".post_type = 'page'") !== false)
			{
				$query = str_replace("WHERE 1=1  AND ".$wpdb->posts.".post_type = 'page'",
									 "WHERE 1=1  AND ".$wpdb->posts.".post_type = 'page' AND scripturelog = 0",
									$query);
			}
			
			return $query;
		}
		
		function parseScriptureLogPageId($objQuery)
		{
			global $wpdb;
			if ('' != $objQuery->query['page_id'] && ( ($objQuery->query['pagename'] && $objQuery->is_posts_page) ) || '' != $objQuery->query['name'] || '' != $objQuery->query['p'] )
			{
				$strSql = "SELECT COUNT(1) FROM ".$wpdb->posts." WHERE scripturelog <> 0 AND ID = ".$objQuery->query['page_id'];
				$intScripturePageCount = $wpdb->get_var($wpdb->prepare($strSql));
				if ($intScripturePageCount > 0)
				{
					$objQuery->is_page = 1;
					$objQuery->is_singular = 1;
					$objQuery->is_single = '';
					$objQuery->is_posts_page = '';
					$objQuery->query['pagename'] = '';
					$objQuery->query['year'] = '';
					$objQuery->query['monthnum'] = '';
					$objQuery->query['day'] = '';
					$objQuery->query['name'] = '';
					$objQuery->query['p'] = '';
					$objQuery->queried_object = null;
				}
			}
		}
	}
}

if (class_exists("Scripturelog"))
{
	$objScriptureLog = new ScriptureLog();
}

if (isset($objScriptureLog))
{
	register_activation_hook(__FILE__,array(&$objScriptureLog,'activate'));
	register_uninstall_hook(__FILE__,array(&$objScriptureLog,'uninstall'));

	add_action('widgets_init', array(&$objScriptureLog,'initWidget'));
	add_action('edit_page_form', array(&$objScriptureLog,'addTagFormToPages'));
	add_filter('posts_where', array(&$objScriptureLog,'includePagesInWhereClause'));
	add_filter('query', array(&$objScriptureLog,'excludeScriptureLogPages'));
	add_action('parse_query', array(&$objScriptureLog,'parseScriptureLogPageId'));
	add_action('admin_menu', array(&$objScriptureLog,'settingsMenu'));
	
	if (!function_exists('displayScriptureLogWidget'))
	{
		function displayScriptureLogWidget($args)
		{
			global $wpdb;
			global $post;
			
			$page_parent = 0;
			$page_parent_parent = 0;
			if ($post->post_parent)
			{
				$strSql = "SELECT ID,post_parent, post_title FROM ".$wpdb->posts." WHERE scripturelog <> 0 AND ID = ".$post->post_parent.";";
				$pages = $wpdb->get_results($strSql);
				$page_parent = $pages[0];
				if ($page_parent->post_parent)
				{
					$strSql = "SELECT ID,post_parent, post_title FROM ".$wpdb->posts." WHERE scripturelog <> 0 AND ID = ".$page_parent->post_parent.";";
					$pages = $wpdb->get_results($strSql);
					$page_parent_parent = $pages[0];
				}
			}
			
			$strSql = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE scripturelog <> 0 AND post_parent = 0 ORDER BY menu_order;";
			$bookPages = $wpdb->get_results($strSql);
			
			extract($args);
			echo $before_widget;
			echo $before_title . 'ScriptureLog' . $after_title;
			echo '<ul>';
			foreach($bookPages as $bookpage)
			{
				echo '<li class="page_item page_item-'.$bookpage->ID.' scripturelog_page '.(($post->ID==$bookpage->ID)?'scripturelog_page_current':'').'"><a href="?page_id='.$bookpage->ID.'">'.$bookpage->post_title.'</a>';
				echo '<ul>';
				if ($post->ID==$bookpage->ID || $post->post_parent==$bookpage->ID || $page_parent->post_parent==$bookpage->ID || $page_parent_parent->post_parent==$bookpage->ID)
				{
					$strSql = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE scripturelog <> 0 AND post_parent = ".$bookpage->ID." ORDER BY menu_order;";
					$pages = $wpdb->get_results($strSql);
					foreach($pages as $page)
					{
						echo '<li class="page_item page_item-'.$page->ID.' scripturelog_page '.(($post->ID==$page->ID || $post->post_parent==$page->ID || $page_parent->post_parent==$page->ID)?'scripturelog_page_current':'').'"><a href="?page_id='.$page->ID.'">'.$page->post_title.'</a></li>';
					}
				}
				echo '</ul></li>';
			}
			echo '</ul>';
			echo $after_widget;
		}
	}
}

?>
