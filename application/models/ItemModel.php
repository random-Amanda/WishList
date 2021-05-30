<?php

class ItemModel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function getUID($itemid)
    {
        $this->db->select('UID');
        $this->db->from('LIST');
        $this->db->join('ITEM', 'ITEM.LIST_ID = LIST.ID');
        $this->db->where('ITEM.ID', $itemid);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->row()->UID;
        } else {
            return null;
        }
    }

    public function getItem($itemid)
    {
        $this->db->select('
        TITLE,
        DESCRIPTION,
        URL,
        IMG,
        PRICE,
        PRIORITY,
        QTY');
        $this->db->from('ITEM');
        $this->db->where('ID', $itemid);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $item = (object) [
                'title' =>  $query->row()->TITLE,
                'price' =>  $query->row()->PRICE,
                'itemdesc' =>  $query->row()->DESCRIPTION,
                'url' =>  $query->row()->URL,
                'img' =>  $query->row()->IMG,
                'qty' =>  $query->row()->QTY,
                'priority' =>  $query->row()->PRIORITY == 1 ? "Must-Have"
                    : ($query->row()->PRIORITY == 2 ? "Would be Nice to Have"
                        : ($query->row()->PRIORITY == 3 ? "If you can" : ""))
            ];
            return $item;
        } else {
            return null;
        }
    }

    public function createItem($payload)
    {
        $item = (object) [
            'TITLE' => $payload['title'],
            'URL' => $payload['url'],
            'PRICE' => $payload['price'],
            'PRIORITY' => $payload['priority'],
            'LIST_ID' => $payload['lid'],
            'QTY' => $payload['qty']
        ];
        if (array_key_exists('itemdesc', $payload)) {
            $item->DESCRIPTION = $payload['itemdesc'];
        }
        if (array_key_exists('img', $payload)) {
            $item->IMG = $payload['img'];
        }
        $this->db->insert('ITEM', $item);
        $item_id = $this->db->insert_id();
        if (!is_null($item_id)) {
            return $item_id;
        } else {
            return null;
        }
    }

    public function updateItem($payload, $itemid)
    {
        $this->db->trans_begin();
        if (array_key_exists('itemdesc', $payload)) {
            $this->db->set('DESCRIPTION', $payload['itemdesc']);
        }
        if (array_key_exists('img', $payload)) {
            $this->db->set('IMG', $payload['img']);
        }
        if (array_key_exists('title', $payload)) {
            $this->db->set('TITLE', $payload['title']);
        }
        if (array_key_exists('url', $payload)) {
            $this->db->set('URL', $payload['url']);
        }
        if (array_key_exists('price', $payload)) {
            $this->db->set('PRICE', $payload['price']);
        }
        if (array_key_exists('priority', $payload)) {
            $this->db->set('PRIORITY', $payload['priority']);
        }
        if (array_key_exists('lid', $payload)) {
            $this->db->set('LIST_ID', $payload['lid']);
        }
        if (array_key_exists('qty', $payload)) {
            $this->db->set('QTY', $payload['qty']);
        }
        $this->db->where('ID', $itemid);
        $this->db->update('ITEM');
        if ($this->db->trans_status() === FALSE) {
            log_message('error', "DB ERROR: " . print_r($this->db->error(), TRUE));
            $this->db->trans_rollback();
            return null;
        } else {
            $this->db->trans_commit();
            return $itemid;
        }
    }

    public function deleteItem($itemid)
    {
        $this->db->trans_begin();
        $this->db->where('ID', $itemid);
        $this->db->delete('ITEM');
        if ($this->db->trans_status() === FALSE) {
            log_message('error', "DB ERROR: " . print_r($this->db->error(), TRUE));
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}
