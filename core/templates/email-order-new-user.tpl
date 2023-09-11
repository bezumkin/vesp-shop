{extends 'email.tpl'}

{block 'content'}
    <pre>{print_r($order, true)}</pre>
    <pre>{print_r($user, true)}</pre>
    <pre>{print_r($address, true)}</pre>
    <pre>{print_r($products, true)}</pre>
{/block}