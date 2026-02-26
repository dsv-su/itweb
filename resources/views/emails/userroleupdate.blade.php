Permissions Update Notification<br><br>

Dear <b>{{$user->name}}</b>,
<br><br>
We would like to inform you that your account and roles have been updated. Your new permissions are now active.
<br><br>
If you have any questions or require further information about your updated roles, please don't hesitate to contact, Tuija Lehtonen (tuija@dsv.su.se), or helpdesk.
<br><br>
Your roles have been updated to:
<br><br>
@foreach($user->roles() as $role)
        @if($role->title == 'staff_education')
            <b>STAFF</b> - Manage the creation, editing, and publishing of digital content, encompassing articles, news posts, images, and other assets within the education sector.
        @elseif ($role->title == 'staff_administration')
            <b>ADMIN</b> - Create, edit, and publish digital content, such as articles, news posts, images, and other assets, within administrative contexts.
        @elseif ($role->title == 'staff_phd')
            <b>PHD</b> - Create, edit, and publish digital content, encompassing articles, news posts, images, and other assets within the realm of PhD studies.
        @elseif ($role->title == 'staff_research')
            <b>RESEARCH</b> - Create, edit, and publish digital content, including articles, news posts, images, and other assets within the field of research.
        @elseif ($role->title == 'staff_premises')
            <b>PREMISES</b> - Create, edit, and publish digital content, such as articles, news posts, images, and other assets related to premises.
        @elseif ($role->title == 'staff_it')
            <b>IT</b> - Create, edit, and publish digital content, including articles, news posts, images, and other assets within the IT domain.
        @elseif ($role->title == 'financial_officer')
            <b>FINACIAL OFFICER</b> - Review, approve, deny, or return user requests for the DSV department.
        @elseif ($role->title == 'project_leader')
            <b>PROJECT LEADER</b> - Review, approve, deny, or return user requests within your project group.
        @elseif ($role->title == 'unit_head')
            <b>UNIT HEAD</b> - Review, grant, deny, or return user requests within your unit.
        @elseif ($role->title == 'vice_head')
            <b>VICE HEAD</b> - Review, grant, deny, or return project proposals within your unit.
        @elseif ($role->title == 'site_administrator')
            <b>ADMINISTRATOR</b> - Full administrative access to the CMS, including managing, configuring, and maintaining the system. Responsibilities also include user management and role assignment, ensuring that user roles and permissions are correctly configured.
        @else
            <b>{{$role->title}}</b>
        @endif
        <br><br>
@endforeach

@foreach($user->groups() as $group)

        @if($group->title == 'UtbildningsAdmin')
            <b>UTBILDNINGSADMINISTRATION</b> - Manage the creation, editing, and publishing of digital content, news posts, images, and other assets within the education sector.
        @elseif ($group->title == 'Personal')
            <b>ADMINISTRATION</b> - Create, edit, and publish digital content, such as articles, news posts, images, and other assets, within administrative contexts.
        @elseif ($group->title == 'DoktorandAdmin')
            <b>PHD ADMINISTRATION</b> - Create, edit, and publish digital content, encompassing articles, news posts, images, and other assets within the realm of PhD studies.
        @elseif ($group->title == 'ForskarAdmin')
            <b>RESEARCH ADMINISTRATION</b> - Create, edit, and publish digital content, including articles, news posts, images, and other assets within the field of research.
        @elseif ($group->title == 'LokalAdmin')
            <b>LOKAL ADMINISTRATION</b> - Create, edit, and publish digital content, such as articles, news posts, images, and other assets related to premises.
        @elseif ($group->title == 'DMC')
            <b>DMC</b> - Create, edit, and publish digital content, including articles, news posts, images, and other assets within the IT domain.
        @elseif ($group->title == 'Ekonomi')
            <b>FINACIAL OFFICER</b> - Review, approve, deny, or return user requests for the DSV department.
        @elseif ($group->title == 'Projektledare')
            <b>PROJECT LEADER</b> - Review, approve, deny, or return user requests within your project group.
        @elseif ($group->title == 'Enhetschef')
            <b>UNIT HEAD</b> - Review, grant, deny, or return user requests within your unit.
        @elseif ($group->title == 'Systemansvarig')
            <b>ADMINISTRATOR</b> - Full administrative access to the CMS, including managing, configuring, and maintaining the system. Responsibilities also include user management and role assignment, ensuring that user roles and permissions are correctly configured.
        @else
            <b>{{$group->title}}</b>
        @endif
        <br><br>


@endforeach

---
<br>
This is an automated email. Please do not reply to this message.
