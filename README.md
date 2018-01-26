Bitbucket Code Coverage

PHP command to convert and publish coverage data to BitBucket server.

This is useful when you want to display clover test coverage information in the diff inside your pull requests. 

Get the plugin for bitbucket here: https://bitbucket.org/atlassian/bitbucket-code-coverage

Install and call this command after your tests have have run.

Usage:

    clover-to-bitbucket coverage:report \
        <coverage.file> \
        <bitbucket.url> \
        <bitbucket.user> \
        <bitbucket.password> \
        <bitbucket.commit.id> \
        [<project.directory>]

The last parameter is optional and defaults to the current working directory.
This is used to convert the absolute paths in the clover xml to paths relative to the repository root. 
 
For authenticating with bitbucket you can use a username and password, or create a personal access token and use that as the password. Only read access is required.

How to create a token: https://confluence.atlassian.com/bitbucketserver/personal-access-tokens-939515499.html
