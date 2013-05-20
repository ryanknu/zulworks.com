<?php

namespace Bar\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Predicate\Predicate;

class ItemTable extends AbstractTableGateway
{
    protected $table = 'payment_service_items';
    const PK = 'id';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Item);
        $this->initialize();
    }
    
    public function getUnsafe($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array(self::PK => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    // gets the items that are in the cart.
    public function getForCart(\Mpn\Cart\Cart $c)
    {
        $ids = array();
        foreach($c->items as $item)
        {
            $ids[$item->item_id] = true;
        }
        $ids = array_keys($ids);
        
        $rowset = $this->select(array('id' => $ids));
        
        foreach($rowset as $item)
        {
            foreach($c->items as $citem)
            {
                if ( $citem->item_id == $item->id )
                {
                    $citem->setItem($item);
                }
            }
        }
    }
    
    /**
     * Get Hidden Items
     * Retrieves the items that the User has hidden from the database
     */
    public function getHidden(\Mpn\Model\User $user)
    {
        $select = $this->getSql()->select();
        $select->join(array('u' => 'user_hidden_items'), $this->table . '.id = u.item_id', array());
        $select->where(array('u.user_id' => $user->id));
        return $this->selectWith($select);
    }
    
    public function getForStudents(array $studs)
    {
        $companies = array();
        foreach($studs as $stud)
        {
            $companies[$stud->school] = $stud->school;
        }
        
        if ( count($companies) )
        {
            $select = $this->getSql()->select();
            
            $select->join(array('p' => 'company_associations'), $this->table . '.company = p.parent', array())
                ->where(array('p.child' => $companies))
                ->where(array('active' => 1))
                ->where('available_items <> 0')
                ->where(array('item_type' => 'general'))
                ->where(array('available_to_users' => 1))
                ->group(array('id'));
            
            // avail check - could be better?
            $select->where('(available = 1 OR (available = 2 AND date_available_start < NOW() AND date_available_end > NOW()) )');
                
            return $this->selectWith($select);
        }
        else
        {
            return array();
        }
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
    
    public function searchTable($query_string)
    {
        if ( $query_string )
        {
            // do something
        }
        
        $sql = $this->getSql();
        $select = $sql->select();
        
        // paginator needs a hydrating result set
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet(null, new User);
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $sql, $resultSet);
        return new \Zend\Paginator\Paginator($adapter);
    }
    
    public function getUserByEmail($email)
    {
        $rowset = $this->select(array('user' => $email));
        $row = $rowset->current();
        if ( !$row )
        {
            throw new \Exception("Could not find user $email");
        }
        return $row;
    }

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser(User $user)
    {
        $data = $user->toArray();
        $id = (int)$user->id;
        if ($id == 0)
        {
            $this->insert($data);
        }
        else
        {
            if ($this->getUser($id))
            {
                $this->update($data, array('id' => $id));
            }
            else
            {
                throw new \Exception('Tried to update nonexistent user');
            }
        }
    }

    public function deleteUser(User $user)
    {
        $this->delete(array('id' => $user->id));
    }
}
