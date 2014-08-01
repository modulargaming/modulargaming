#!/bin/sh
# Script for merging master branch into develop for all modules.

function merge-directories() {
	for i in "${directories[@]}"
	do
		cd "$i"
		echo "--------------"
		echo "-- $i"
		echo "--------------"

		# Update develop branch
		git checkout develop -q
		git pull -q

		# Update master branch
		git checkout master -q
		git pull -q
		
		# Merge develop into master and push
		git merge --no-ff develop
		git push -u origin master

		cd ../
	done
}

# Modules
directories=("admin" "core" "forum" "game" "item" "message" "payment" "pet" "search" "user")

cd ../modulargaming
merge-directories

# Themes
directories=("admin" "default")

cd ../themes
merge-directories