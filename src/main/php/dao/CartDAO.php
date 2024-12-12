<?php

declare(strict_types=1);

namespace dao;

use auth\AuthManager;
use repositories\Database;
use domain\Cart;
use domain\CartItem;

/**
 * Responsible for managing 'cart' table.
 */
class CartDAO extends DAO
{
    //-------------------------------------------------------------------------
    //        Constructor
    //-------------------------------------------------------------------------
    /**
     * Creates 'cart' table manager.
     *
     * @param       Database $db Database
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    //-------------------------------------------------------------------------
    //        Methods
    //-------------------------------------------------------------------------
    /**
     * Adds an item to the cart.
     *
     * @param       int $idUser User ID
     * @param       int $idBundle Bundle ID
     * @param       int $quantity Quantity of the bundle
     */
    public function addItem(int $idUser, int $idBundle, int $quantity): void
    {
        $this->withQuery("
            INSERT INTO cart (id_student, id_bundle, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = 1
        ");
        $this->runQueryWithArguments($idUser, $idBundle, $quantity);
    }

    /**
     * Removes an item from the cart.
     *
     * @param       int $idUser User ID
     * @param       int $idBundle Bundle ID
     */
    public function removeItem(int $idUser, int $idBundle): void
    {
        $this->withQuery("
            DELETE FROM cart
            WHERE id_student = ? AND id_bundle = ?
        ");
        $this->runQueryWithArguments($idUser, $idBundle);
    }

    /**
     * Gets all items in the cart for a user.
     *
     * @param       int $idUser User ID
     * @return      Cart Cart containing all items
     */
    public function getCart(int $idUser): Cart
    {
        $this->withQuery("
            SELECT      c.id_bundle, b.name, b.price, c.quantity, b.logo
            FROM        cart c
            JOIN        bundles b ON c.id_bundle = b.id_bundle
            WHERE       c.id_student = ?
        ");
        $this->runQueryWithArguments($idUser);

        return $this->parseCartResponseQuery();
    }

    private function parseCartResponseQuery(): Cart
    {
        if (!$this->hasResponseQuery()) {
            return new Cart([]);
        }

        $items = [];

        foreach ($this->getAllResponseQuery() as $cartItemRaw) {
            $items[] = new CartItem(
                (int)$cartItemRaw['id_bundle'],
                $cartItemRaw['name'],
                (float)$cartItemRaw['price'],
                (int)$cartItemRaw['quantity'],
                $cartItemRaw['logo']
            );
        }

        return new Cart($items);
    }

    /**
     * Gets the total price of all items in the cart for a user.
     *
     * @param       int $idUser User ID
     * @return      float Total price of all items
     */
    public function getTotal(int $idUser): float
    {
        $this->withQuery("
            SELECT SUM(b.price * c.quantity) as total
            FROM cart c
            JOIN bundles b ON c.id_bundle = b.id_bundle
            WHERE c.id_student = ?
        ");
        $this->runQueryWithArguments($idUser);

        if (!$this->hasResponseQuery()) {
            return 0.0;
        }

        $result = $this->getResponseQuery();
        return (float)$result['total'];
    }

    // public function checkout(int $idUser): bool
    // {
    //     $cart = $this->getCart($idUser);
    //     $cartItems = $cart->getItems();

    //     for ($i = 0; $i < sizeof($cartItems); $i++) {
    //         $studentsDao = new StudentsDAO(
    //             $this->db,
    //             AuthManager::getLoggedIn($this->db)->getId()
    //         );

    //         $studentsDao->addBundle($cartItems[$i]->getIdBundle());
    //     }

    //     $this->clearCart($idUser);

    //     return true;
    // }

    /**
     * Clears the cart for a user.
     *
     * @param       int $idUser User ID
     */
    public function clearCart(int $idUser): array
    {
        $cart = $this->getCart($idUser);
        $cartItems = $cart->getItems();

        $this->withQuery("
            DELETE FROM cart
            WHERE id_student = ?
        ");
        $this->runQueryWithArguments($idUser);

        return $cartItems;
    }
}
