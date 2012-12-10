<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <style type="text/css" media="screen">
{foreach from=$css item=icss}
      @import "{$path}{$icss}";
{/foreach}
      @import "{$path}styles/global.css";
      @import "{$path}styles/normal2.css";
      @import "{$path}styles/jtip.css";
      @import "{$path}styles/thickbox.css";
    </style>
{foreach from=$js_ie item=ijs}
    <!--[if IE]><script type="text/javascript" src="{$path}{$ijs}"></script><![endif]-->
{/foreach}
{foreach from=$js item=ijs}
    <script type="text/javascript" src="{$path}{$ijs}"></script>
{/foreach}
    <title>{if $title}{$title} – {/if}Comparo²</title>
{if $js_head}
    <script type="text/javascript">
{$js_head|indent:6}
    </script>
{/if}
  </head>
  <body>
    <div id="wrap">
{if $user->isLogged()}
  <div id="userBar">
    <div>Connecté en tant que {$user->username()|convert} | <a href="login.php?disconnect=1">Se déconnecter</a></div>
  </div>
{/if}