# Item class

The [Item] class aims to make it easier for you to handle in-game items.

## Initiating the class

There are several ways to initiate the class:

 - By passing an item's name

    ~~~
    $item = Item::factory('apple');
    ~~~

 - By passing an item's id

    ~~~
    $item = Item::factory(1);
    ~~~

 - By passing a Model_Item instance

    ~~~
    $reference = ORM::factory('Item')
        ->where('name', '=', 'apple')
        ->find();

    $item = Item::factory($reference);
    ~~~

## Giving the user an item
The item class has a `to_user` method that takes 4 arguments:

  1. A [Model_User] instance or a user's id you want to send this item to
  2. For logging purpose you enter where you're sending this item from (e.g. shop, quest, game,...)
  3. How many copies the user will get (defaults to 1)
  4. Which location to send the item(s) to (defaults to inventory)
~~~
    //sending the item to a user from within a game
    $item->to_user($user, 'game');

    //sending multiple copies
    $item->to_user($user, 'game', 3);

    //Giving the user 2 items and put them in the safe
    $item->to_user($user, 'game', 2, 'safe');
~~~

## Checking if a user has the item

The `user_has` method is used for this and takes 3 arguments

1. The location where the item is located (defaults to inventory)
2. How many copies the user should have (defaults to 1)
3. Which [Model_User] instance this applies to (defaults to the logged in user)

This method will always return a boolean you can check.

~~~
    //checking if the logged in user has at least 1 copy of the item in his inventory
    $item->user_has();

    //checking if the logged in user has has at least 1 copy in his safe
    $item->user_has('safe');

    //checking if the user has at least 5 of these in his inventory
    $item->user_has('inventory', 5);

    //checking if a user with 5as his id has 2 copies in his gallery
    $other_user = ORM::factory('User', 5);

    $item->user_has('gallery', 2, $other_user);
~~~

## Getting a list of items based on their location