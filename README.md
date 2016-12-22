# SyncApi
This is the Webpage version for the http://github.com/famoser/SyncApi

[![Travis Build Status](https://travis-ci.org/famoser/SyncApi.Webpage.svg?branch=master)](https://travis-ci.org/famoser/SyncApi.Webpage)
[![Code Climate](https://codeclimate.com/github/famoser/SyncApi.Webpage/badges/gpa.svg)](https://codeclimate.com/github/famoser/SyncApi.Webpage)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0049282fe1b3437ba8321ec244a3ea93)](https://www.codacy.com/app/famoser/SyncApi-Webpage?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=famoser/SyncApi.Webpage&amp;utm_campaign=Badge_Grade)
[![Scrutinizer](https://scrutinizer-ci.com/g/famoser/SyncApi.Webpage/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/famoser/SyncApi.Webpage)
[![Test Coverage](https://codeclimate.com/github/famoser/SyncApi.Webpage/badges/coverage.svg)](https://codeclimate.com/github/famoser/SyncApi.Webpage/coverage)

a nuget portable library to sync entities in a very convenient way.

This library manages the synchronization between multiple installation of an application of the same user. 
You may save any entity you want, and it will be synced typesafe. Included is some sort of version control (you will be able to access older versions of an entity).

It uses the roaming storage provided by UWP to save user information, all actual data is synced over a php api.
The library is heavily customizable, while allowing an easy and straightforward approach for simple use cases.

Code example:

```c#
//construct the api helper (storage service is implementated in Famoser.UniversalEssentials for UWP)
IStorageService ss = new StorageService();
var helper = new SyncApiHelper(ss, "my_application_name", "https://api.mywebpage.ch");

//get my repository
var repo = helper.ResolveRepository<NoteModel>();

//save my model
await repo.SaveAsync(new NoteModel { Content = "Hallo Welt!" });

//retrieve it later on
ObservableCollection<NoteModel> coll = await repo.GetAllAsync();
```
