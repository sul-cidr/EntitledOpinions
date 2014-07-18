# Stanford Sites Jumpstart Personal

An installation profile for personal accounts.

For more information please see:
https://github.com/SU-SWS/stanford_sites_jumpstart/blob/7.x-3.x-dev/README.md

## Installing VIA Drush

When installing via drush you can use the following flags

    install_configure_form.capapi_username
    install_configure_form.capapi_password
    install_configure_form.requester_sunetid
    install_configure_form.requester_name
    install_configure_form.requester_email

    eg: drush si stanford_sites_jumpstart_personal [any or all of the above options]

## Installation Logic

The idea behind this profile is to allow children & parent profiles to add to, alter, and remove dependencies and installation tasks then install all of them in order. This will allow you to use logic from a parent profile but make any necessary changes. The installation process has been altered slightly with this profile and the new order of operations are

1. **Choose Profile**
2. **Choose Language**
3. **Verify Requirements**   | Altered to verify all child/parent profiles.
4. **Install System** | Core.
5. **Install standard assets** | Standard profile dependant modules.
6. **Install common asses** | The entire install tree of declared dependency modules are installed here. This includes the Stanford profile modules, the base profile modules, all sub profile modules, and the profile's hook_install() themselves.
7. **Configuration Form** | Altered to allow all profiles to add/change this form
8. **Installation Tasks** | Install tasks are defined in each profile and will be executed in order from the top most parent installation profile to the bottom most child. Tasks can be altered/removed by children profiles.
9. **Finish** | Finished notification page.

## Profile

See [API.md](https://github.com/SU-SWS/stanford_sites_jumpstart/blob/7.x-3.x-dev/API.md) for more.


