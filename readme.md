# OParl Specs - Website

- get commits
app('github')->api('repo')->commits()->all('OParl', 'specs', ['sha' => 'master']);

- get file info
app('github')->api('repo')->contents()->show('OParl', 'specs', 'dokument/master/...');