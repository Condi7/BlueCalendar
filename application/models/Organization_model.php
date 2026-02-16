<?php
/**
 * This Model contains all the business logic and the persistence layer for the organization tree.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This class allows to manage the organization of of users. Users can be attached to a node of a tree.
 * These nodes are called 'entities' and can be 'departments' or 'sub-departments', 'groups', etc.
 * It allows to use filters on a part of your structure, whatever your organization is.
 */
class Organization_model extends CI_Model {

    /**
     * Default constructor
     */
    public function __construct() {

    }

    /**
     * Get the department details of an employee (label and ID)
     * @param int $employeeId User identifier
     * @return array department details
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getDepartment($employeeId) {
        $this->db->select('organization.*');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->where('users.id', $employeeId);
        $query = $this->db->get();
        return $query->result()[0];
    }

    /**
     * Get the label of a given entity id
     * @param int $id Identifier of the entity
     * @return string name of the entity
     */
    public function getName($id) {
        $this->db->from('organization');
        $this->db->where("id", $id);
        $query = $this->db->get();
        $record = $query->result_array();
        if(count($record) > 0) {
            return $record[0]['name'];
        } else {
            return '';
        }
    }

    /**
     * List all entities of the organisation
     * @return array all entities of the organization sorted out by id and name
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getAllEntities() {
        $this->db->from('organization');
        $this->db->order_by("parent_id", "desc");
        $this->db->order_by("name", "asc");
        return $this->db->get();
    }

    /**
     * Get all children of an entity
     * @param int $id identifier of the entity
     * @return array list of entity identifiers
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getAllChildren($id) {
        $query = 'SELECT GetFamilyTree(id) as id' .
                    ' FROM organization' .
                    ' WHERE id =' . $id;
        $query = $this->db->query($query);
        if(!$query) {
            $arr = [];
        } else {
            $arr = $query->result_array();
        }
        return $arr;
    }

    /**
     * Move an entity into the organization
     * @param int $id identifier of the entity
     * @param int $parent_id new parent id of the entity
     * @return type result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function move($id, $parent_id) {
        $data = array(
            'parent_id' => $parent_id
        );
        $this->db->where('id', $id);
        return $this->db->update('organization', $data);
    }

    /**
     * Add an employee into an entity of the organization
     * @param int $id identifier of the employee
     * @param int $entity identifier of the entity
     * @return type result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function attachEmployee($id, $entity) {
        $data = array(
            'organization' => $entity
        );
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Cascade delete children and set employees' org to NULL
     * @param int $entity identifier of the entity
     * @return type result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete($entity) {
        $list = $this->getAllChildren($entity);
        //Detach all employees
        $data = array(
            'organization' => NULL
        );
        $ids = array();
        if (strlen($list[0]['id']) > 0) {
            $ids = explode(",", $list[0]['id']);
        }
        array_push($ids, $entity);
        $this->db->where_in('organization', $ids);
        $res1 = $this->db->update('users', $data);
        //Delete node and its children
        $this->db->where_in('id', $ids);
        $res2 = $this->db->delete('organization');
        return $res1 && $res2;
    }

    /**
     * Delete an employee from an entity of the organization
     * @param int $id identifier of the employee
     * @return type result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function detachEmployee($id) {
        $data = array(
            'organization' => NULL
        );
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Rename an entity of the organization
     * @param int $id identifier of the entity
     * @param string $text new text of the entity
     * @return type result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function rename($id, $text) {
        $data = array(
            'name' => $text
        );
        $this->db->where('id', $id);
        return $this->db->update('organization', $data);
    }

    /**
     * Create an entity in the organization
     * @param int $parent_id identifier of the parent entity
     * @param string $text name of the new entity
     * @return type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create($parent_id, $text) {
        $data = array(
            'name' => $text,
            'parent_id' => $parent_id
        );
        return $this->db->insert('organization', $data);
    }

    /**
     * Copy an entity in the organization
     * @param int $id identifier of the source entity
     * @param int $parent_id identifier of the new parent entity
     * @return type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function copy($id, $parent_id) {
        $this->db->from('organization');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $row = $query->row();
        $data = array(
            'name' => $row->name,
            'parent_id' => $parent_id
        );
        return $this->db->insert('organization', $data);
    }

    /**
     * Returns the list of the employees attached to an entity
     * @param int $id identifier of the entity
     * @return array Result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function employees($id) {
        $this->db->select('id, firstname, lastname, email, datehired');
        $this->db->from('users');
        $this->db->where('organization', $id);
        $this->db->order_by('lastname', 'asc');
        $this->db->order_by('firstname', 'asc');
        return $this->db->get();
    }

    /**
     * Returns the list of the employees attached to an entity
     * @param int $id identifier of the entity
     * @param bool $children Include sub department in the query
     * @return  array Result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function allEmployees($id, $children = FALSE) {
        $this->db->select('users.id, users.identifier, users.firstname, users.lastname, users.datehired');
        $this->db->select('organization.name as department, positions.name as position, contracts.name as contract');
        $this->db->select('contracts.id as contract_id');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('positions', 'positions.id  = users.position', 'left');
        $this->db->join('contracts', 'contracts.id  = users.contract', 'left');
        if ($children === TRUE) {
            $this->load->model('organization_model');
            $list = $this->organization_model->getAllChildren($id);
            $ids = array();
            if (count($list) > 0) {
                if ($list[0]['id'] != '') {
                    $ids = explode(",", $list[0]['id']);
                    array_push($ids, $id);
                    $this->db->where_in('organization.id', $ids);
                } else {
                    $this->db->where('organization.id', $id);
                }
            }
        } else {
            $this->db->where('organization.id', $id);
        }
        $this->db->order_by('lastname', 'asc');
        $this->db->order_by('firstname', 'asc');
        $employees = $this->db->get()->result();
        return $employees;
    }

    /**
     * Add an employee into an entity of the organization
     * @param int $id identifier of the employee
     * @param int $entity identifier of the entity
     * @return int result of the query
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function setSupervisor($id, $entity, $slot = 1) {
        $field = ((int) $slot === 2) ? 'supervisor2' : 'supervisor';
        $data = array(
            $field => $id
        );
        $this->db->where('id', $entity);
        return $this->db->update('organization', $data);
    }

    /**
     * Returns the supervisors of an entity
     * @param int $entity identifier of the entity
     * @return array identifiers, names and emails of supervisors
     */
    public function getSupervisors($entity) {
        $this->db->select("organization.supervisor as supervisor1_id, CONCAT(u1.firstname, ' ', u1.lastname) as supervisor1_name, u1.email as supervisor1_email", FALSE);
        $this->db->select("organization.supervisor2 as supervisor2_id, CONCAT(u2.firstname, ' ', u2.lastname) as supervisor2_name, u2.email as supervisor2_email", FALSE);
        $this->db->from('organization');
        $this->db->join('users as u1', 'u1.id = organization.supervisor', 'left');
        $this->db->join('users as u2', 'u2.id = organization.supervisor2', 'left');
        $this->db->where('organization.id', $entity);
        $result = $this->db->get()->row_array();

        if (empty($result)) {
            return array(
                'supervisor1' => NULL,
                'supervisor2' => NULL
            );
        }

        $supervisor1 = NULL;
        if (!empty($result['supervisor1_id'])) {
            $supervisor1 = array(
                'id' => $result['supervisor1_id'],
                'username' => $result['supervisor1_name'],
                'email' => $result['supervisor1_email']
            );
        }

        $supervisor2 = NULL;
        if (!empty($result['supervisor2_id'])) {
            $supervisor2 = array(
                'id' => $result['supervisor2_id'],
                'username' => $result['supervisor2_name'],
                'email' => $result['supervisor2_email']
            );
        }

        return array(
            'supervisor1' => $supervisor1,
            'supervisor2' => $supervisor2
        );
    }

    /**
     * Returns the supervisor of an entity
     * @param int $entity identifier of the entity
     * @return object identifier of supervisor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getSupervisor($entity) {
        $supervisors = $this->getSupervisors($entity);
        if (array_key_exists('supervisor1', $supervisors)) {
            if (!is_null($supervisors['supervisor1'])) {
                return (object) $supervisors['supervisor1'];
            }
        }
        return NULL;
    }

    /**
     * Returns a comma-separated list of e-mails of supervisors (1 and 2)
     * @param int $entity identifier of the entity
     * @return string comma-separated e-mail list
     */
    public function getSupervisorsMails($entity) {
        $supervisors = $this->getSupervisors($entity);
        $mails = array();

        if (!is_null($supervisors['supervisor1']) && !empty($supervisors['supervisor1']['email'])) {
            $mails[] = $supervisors['supervisor1']['email'];
        }
        if (!is_null($supervisors['supervisor2']) && !empty($supervisors['supervisor2']['email'])) {
            $mails[] = $supervisors['supervisor2']['email'];
        }

        $mails = array_values(array_unique($mails));
        if (count($mails) == 0) {
            return '';
        }
        return implode(',', $mails);
    }
}
