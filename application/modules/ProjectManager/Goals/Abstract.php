<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    ProjectManager_Goals_Abstract
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Monday 16th of December 2019 09:10AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class ProjectManager_Goals_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'goals_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'goals_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'ProjectManager_Goals';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );


    /**
     * 
     */
	public static function getGoals( array $where = null )  
    {
        $options = array();
        $goals = ProjectManager_Goals::getInstance()->select( null, $where );
        foreach( $goals as $goal )
        {
            if( empty( $goal['article_url'] ) )
            {
                continue;
            }
            $projectInfo = Application_Article_Abstract::loadPostData( $goal['article_url'] );
            if( ! empty( $projectInfo['article_title'] ) )
            {
                $goal['goal'] = $goal['goal'] . ' (' . $projectInfo['article_title']  . ')';
            }
            $options[$goal['goals_id']] = $goal['goal'];
        }
        asort( $options );
        //      $options = array( '' => 'Please Select...' ) + $options;

        return $options;
    }

    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;

		$fieldset = new Ayoola_Form_Element;
        $fieldset->addElement( array( 'name' => 'goal', 'label' => 'Set a Goal', 'placeholder' => 'Enter a goal...', 'type' => 'InputText', 'value' => @$values['goal'] ) ); 

		if( empty( $_GET['article_url'] ) && ! $this->getParameter( 'ignore_article_url' ) && ! @$values['article_url'] )
		{
			$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'InputText', 'value' => @$values['article_url'] ) ); 
		}

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
