{extends 'email.tpl'}

{block 'content'}
    <pre>{print_r($data, true)}</pre>
{/block}