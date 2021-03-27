<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    ProjectManager_Tasks_Abstract
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Monday 16th of December 2019 09:11AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class ProjectManager_Tasks_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'tasks_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'tasks_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'ProjectManager_Tasks';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );
    
    protected static $_timeTable = array(
        'minute' => 60,
        'hour' => 3600,
        'day' => 86400,
        'week' => 604800,
        'month' => 2592000,
        'year' => 31536000,
    );


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
        switch( @$_GET['task_edit_mode'] )    
        {
            case 'completion':
                $legend = 'Mark task as completed';
                $fieldset->addElement( array( 'name' => 'completion_time', 'label' => 'Set Completion Time', 'type' => 'DateTime', 'value' => @$values['completion_time'] ) ); 
            break;
            default:
                $fieldset->addElement( array( 'name' => 'task', 'label' => 'Task', 'type' => 'InputText', 'value' => @$values['task'] ) ); 
                $fieldset->addElement( array( 'name' => 'duration', 'label' => 'Duration', 'type' => 'Select', 'value' => @$values['duration'], 'style' => 'width:100px;' ), array_combine( range( 1, 30 ), range( 1, 30 ) ) ); 
                $fieldset->addElement( array( 'name' => 'duration_time', 'label' => '', 'type' => 'Select', 'value' => @$values['duration_time'] ? : 86400, 'style' => 'width:100px;' ), array_flip( self::$_timeTable ) ); 
                $fieldset->addElement( array( 'name' => 'time', 'label' => 'Start Time', 'type' => 'DateTime', 'value' => @$values['time'] ) ); 
                $fieldset->addElement( array( 'name' => 'completion_time', 'label' => '', 'type' => 'Hidden', 'value' => null ) );

                $emailType = 'MultipleInputText';
                $emailOptions = array();
                if( $this->getParameter( 'email_address' ) && is_array( $this->getParameter( 'email_address' ) ) ) 
                {
                    $emailType = 'SelectMultiple';
                    $emailOptions = array_combine( $this->getParameter( 'email_address' ), $this->getParameter( 'email_address' ) );
                }
                $fieldset->addElement( array( 'name' => 'email_address', 'label' => 'Assign to:', 'multiple' => 'multiple', 'placeholder' => 'example@mail.com', 'type' => $emailType, 'value' => @$values['email_address'] ? : array( Ayoola_Application::getUserInfo( 'email') ) ), $emailOptions ); 
                $fieldset->addFilter( 'email_address', array( 'LowerCase' ) );
                if( empty( $_GET['goals_id'] ) )
                {
                    if( $values['goals_id'] && ProjectManager_Goals::getInstance()->selectOne( null, array( 'goals_id' => $values['goals_id'] ) ) )
                    {
                        $fieldset->addElement( array( 'name' => 'goals_id', 'type' => 'Hidden', 'value' => @$values['goals_id'] ) ); 
                    }
                    else
                    {
                        $where = array();
                        if( ! empty( $_GET['article_url'] ) )
                        {
                            $where['article_url'] = $_GET['article_url'];
                        }
                        if( $options = ProjectManager_Goals_Abstract::getGoals( $where ) )
                        {
                            $fieldset->addElement( array( 'name' => 'goals_id', 'label' => 'Task Goal', 'type' => 'Select', 'value' => @$values['goals_id'] ), $options );
                        }
                        
                    }
                }
            break;
        }
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
