<h1>Frontend Bilemo</h1>
<a href="https://www.codacy.com/app/Thibok/Frontend_Bilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Thibok/Frontend_Bilemo&amp;utm_campaign=Badge_Grade"><img src="https://api.codacy.com/project/badge/Grade/2ee565788ee4484eb691748c2015ec1d"/></a>
<p>Welcome on the Bilemo Frontend app ! This app is the Frontend part of the <a href="https://github.com/Thibok/Bilemo">Bilemo Api</a><br/>
<h2>Prerequisites</h2>
<ul>
  <li>PHP 7</li>
  <li>Mysql</li>
  <li>Apache</li>
</ul>
<h2>Framework</h2>
<ul>
  <li>Symfony</li>
</ul>
<h2>ORM</h2>
<ul>
  <li>Doctrine</li>
</ul>
<h2>Bundles</h2>
<ul>
  <li>csa/guzzle-bundle</li>
  <li>jms/serializer-bundle</li>
</ul>
<h2>Getting started</h2>
<h4>Create Facebook app</h4>
<p>Before you install the project, go to the <a href="https://developers.facebook.com/">Facebook Developers</a> site and sign in.Then go to your space <a href="https://developers.facebook.com/apps">here</a> and create a new app.</p>
<h2>Installation</h2>
<h4>Clone project :</h4>
<pre>git clone https://github.com/Thibok/Bilemo.git</pre>
<h4>Install dependencies :</h4>
<p>For fb_client_id and fb_client_secret ask during the composer installation, you can find your app Id in your Facebook Developers Dashboard and for secret go to <strong>Settings</strong>, <strong>General</strong> and display your secret key</p>
<pre>composer install</pre>
<h4>Create database :</h4>
<pre>php bin/console doctrine:database:create</pre>
<h4>Update schema :</h4>
<pre>php bin/console doctrine:schema:update --force</pre>
<h4>Run It !</h4>
<p>Now you can start your server with this :</p>
<pre>php bin/console server:start</pre>
<strong>And go on the local address !</strong>
<h2>Tests</h2>
<p>If you need run tests :</p> 
<h4>Create test database :</h4>
<pre>php bin/console doctrine:database:create --env=test</pre>
<h4>Update schema :</h4>
<pre>php bin/console doctrine:schema:update --force --env=test</pre>
<h4>Run tests !</h4>
<pre>vendor/bin/phpunit</pre>
<h2>Production</h2>
<p>If you want to use production environment, don't forget :</p>
<h4>Clear cache :</h4>
<pre>php bin/console cache:clear --env="prod"</pre>
