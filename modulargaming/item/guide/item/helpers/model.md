# User's item model

## Moving an item to a different location

## Transferring an item to another user

## Getting an item's name

    //returns the item's name prefixed with its amount
    $user_item->name();

## Change an item's amount

    $user_item->amount('+', 5);

    $user_item->amount('-');

## Define an item's identity

explain the use of parameters and parameter_id

#Item model

## Getting an item's name

    $item = ORM::factory('Item')
        ->where('name,' =', 'apple')
        ->find();

    //just return its name
    $item->name;

    //outputs 1 apple
    $item->name(1);

    //outputs 2 apples
    $item->name(2);

## Getting an item's image

    $item->img();

## Checking if an item is still in circulation

    $item->in_circulation();