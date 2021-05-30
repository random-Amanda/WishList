<?php

class ListModel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getUID($lid)
    {
        $this->db->select('UID');
        $this->db->from('LIST');
        $this->db->where('id', $lid);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            if ($query->row()->UID) {
                return $query->row()->UID;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getListWithItems($listId)
    {
        $list = (object) ['items' => []];
        // get item details
        $this->db->select('
        LIST.NAME AS LISTNAME,
        LIST.DESCRIPTION AS LISTDESC,
        ITEM.ID,
        ITEM.TITLE,            
        ITEM.PRICE,
        ITEM.PRIORITY,         
        ITEM.QTY');
        // ITEM.DESCRIPTION,
        // ITEM.URL,
        // ITEM.IMG,
        $this->db->from('ITEM');
        $this->db->join('LIST', 'LIST.ID = ITEM.LIST_ID');
        $this->db->where('LIST.ID', $listId);
        $this->db->order_by('PRIORITY', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $list->listdesc = $query->result()[0]->LISTDESC;
            $list->listName = $query->result()[0]->LISTNAME;
            // loop result array to build list object
            foreach ($query->result() as $listitem) {
                $item = (object) [];
                $item->itemid = $listitem->ID;
                $item->title = $listitem->TITLE;
                $item->price = $listitem->PRICE;
                //$item->itemdesc = $listitem->DESCRIPTION;
                //$item->url = $listitem->URL;
                //$item->img = $listitem->IMG;
                $item->qty = $listitem->QTY;
                $item->priority = $listitem->PRIORITY == 1 ? "Must-Have"
                    : ($listitem->PRIORITY == 2 ? "Would be Nice to Have"
                        : ($listitem->PRIORITY == 3 ? "If you can" : ""));
                array_push($list->items, $item);
            }
            return $list;
        } else {
            return null;
        }
    }

    public function getList($listId)
    {
        $list = (object) [];
        // get item details
        $this->db->select('
        LIST.NAME AS LISTNAME,
        LIST.DESCRIPTION AS LISTDESC');
        $this->db->from('LIST');
        $this->db->where('LIST.ID', $listId);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $list->listdesc = $query->row()->LISTDESC;
            $list->listName = $query->row()->LISTNAME;
            return $list;
        } else {
            return null;
        }
    }

    public function createList($payload)
    {
        $list = (object) [
            'NAME' => $payload['listName'],
            'UID' => $payload['uid']
        ];
        if (array_key_exists('listdesc', $payload)) {
            $list->DESCRIPTION = $payload['listdesc'];
        }
        $this->db->insert('LIST', $list);
        $list_id = $this->db->insert_id();
        if (!is_null($list_id)) {
            return $list_id;
        } else {
            return null;
        }
    }
}
