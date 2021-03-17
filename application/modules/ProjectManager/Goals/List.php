<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    ProjectManager_Goals_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class ProjectManager_Goals_List extends ProjectManager_Goals_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'Goals';   

    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
      $this->setViewContent( $this->getList() );		
    } 
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList()
    {
		if( ! empty( $_GET['article_url'] ) )
		{
			$this->_dbWhereClause['article_url'] = $_GET['article_url'];
            if( $postData = Application_Article_Abstract::loadPostData( $_GET['article_url'] ) )
            {    
                //  $this->setViewContent(  '' . self::__( '<div class="badnews">Project not found</div>' ) . '', true  );
                //  return false;
                $project = $postData['article_title'];
            }
		}
        elseif(  $this->getParameter( 'project_name' ) )
        {
			$this->_dbWhereClause['article_url'] = $this->getParameter( 'project_name' );
            $project = $this->getParameter( 'project_name' );
        }
        else
        {
            $project = "Untitled Project";
        }
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = self::getObjectTitle();
		$list->hideCheckbox = true;
		$list->noHeader = true;
		$list->setData( $this->getDbData() );
        if( ! $this->getParameter( 'no_list_options' ) )
        {
            $list->setListOptions( 
                array( 
                        'Import' => $project ? '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Goals_Duplicate?article_url=' . $project . '\', \'page_refresh\' );" title="">Import Goal to Project</a>' : null,    
                        'Creator' => $project ? '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Goals_Creator?article_url=' . $project . '\', \'page_refresh\' );" title="">Create a New Goal</a>' : null,    
                        'Timeline' => $_GET['article_url'] ? '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Timeline?article_url=' . $project . '\', \'page_refresh\' );" title="">Goal Timeline</a>' : null,    
                    ) 
            );
        }
        else
        {
            $list->setListOptions( 
                array( 
                        'Creator' => '',    
                    ) 
            );
        }
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No goals set yet.' );
		
		$list->createList
		(
			array(
                    'goal' => array( 'field' => 'goal', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                //    'deadline' => array( 'field' => 'time', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    array( 'field' => 'completion_time', 'value' =>  '%FIELD%', 'value_representation' => array( '' => '', 'pc_paginator_default' => '<i class="fa fa-check"></i>' ) ), 
                    array( 'field' => 'goals_id', 'value' =>  '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Tasks_List?goals_id=%FIELD%\', \'page_refresh\' );" title="">tasks</a>', 'filter' =>  '' ), 
                    '%FIELD% <a style="font-size:smaller;" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Goals_Editor/?' . $this->getIdColumn() . '=%KEY%&article_url=' . @$_GET['article_url'] . '\', \'page_refresh\' );"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
                    '%FIELD% <a style="font-size:smaller;" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/ProjectManager_Goals_Delete/?' . $this->getIdColumn() . '=%KEY%&article_url=' . @$_GET['article_url'] . '\', \'page_refresh\' );"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
