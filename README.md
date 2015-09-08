Capri
=====

Directadmin Capri Skin

This is a repo for the Directadmin skin so we can update it to newer version and make it even better.

We do not include the functions.php here becouse it have the licence check in it!
But we do a version check and update true github

![skin_preview](http://www.outservices.net/images/capri_screens/ca_userlevel.png)

INFO:
=====

Features | Preview
------------ | -------------
**Language changer** |
Now all users can change the language selecting the language from the dropdown menu on the header. There is no more need to search for this (hard to find) option. | ![Language changer](http://www.outservices.net/images/cas_lang.png)	
**2 different color set** |
You can choose between two different color set. A blue based and a grey based. | ![2 different color set](http://www.outservices.net/images/cas_colorset.png)
**Disk and Bandwidth meters** |
New disk utilization and bandwidth animated meters for resellers and users. | ![Disk and Bandwidth meters](http://www.outservices.net/images/cas_lmeters.png)
**Pie charts** |
A nice touch for this nice skin. 3 pie charts for disk utilization, including email space utilization and DB space utilization. We also include pie charts for the admin in the Complete usage statistics for disk and partitions utilization. 	| ![Pie charts](http://www.outservices.net/images/cas_pies.png)
**Password strength meter** |
Every password fields have this little meter that allow any user to measure the password strength, which can be very weak, weak, medium, good or strong. | ![Password strength meter](http://www.outservices.net/images/cas_passste.png)
**Users list** |
The users list table has different color icons to identify if it is admin, reseller or user (yellow, blue or gree). The red one is for suspended users/resellers.Suspended users/reseller are also highlighted in red. This list also have a small image button to suspend or unsuspend users/resellers and another one to delete user/resellers. | ![Users list](http://www.outservices.net/images/cas_suspid.png)
**Pop account meters** |
The POP accounts list has a little usage meter for every account to easily identify a full mailbox. | ![Pop account meters](http://www.outservices.net/images/cas_popmeter.png)	
**Services status** |
We added a services status box on the main page for admins. This panel also have the load average of the server. | ![Services status](http://www.outservices.net/images/cas_lserv.png)	
**Domain owners** |
This is a list of all domains in the server and also included the owners of each domain. You can also switch to a raw list, which is a text-plain list of all domains. Useful for a quick export, for example. | ![Domain owners](http://www.outservices.net/images/cass_alldom.png)	
**Stopped services highlight** |
The services table will highlight in the a stopped services. | ![Stopped services highlight](http://www.outservices.net/images/cas_services.png)	
**Random password** |
Every password field has now a random button to generate a 8 chars random password. | ![Random password](http://www.outservices.net/images/cas_rand.png)	
**Custom logo** |
Now you and your resellers can change the logo image in the skin header. It is very simple. And forget about updates, your custom logo will be there after any update. 	| ![Custom logo](http://www.outservices.net/images/cas_logo.png)	

 
######Note:
This skin was not tested on FreeBSD and may not be compatible with it.

To use the in-skin update function u need to chmod all files and folders under /usr/local/directadmin/data/skin/capri to 777 OR set the owner to admin:admin
After a directadmin the rights will be reset!

: Update 10-03-2015

Functions.php is now included without licence check. 
This improves the overal speed of the skin allot. Also is now posible to use this skin in all directadmin servers u have for FREE!

Instruction for install (from git):
=====

```bash
wget -O capri.sh https://raw.githubusercontent.com/Terrorhawk/Capri/master/install-script
chmod +x capri.sh
./capri.sh
```