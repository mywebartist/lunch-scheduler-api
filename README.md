# Lunch Scheduler

This is a backend system

### Built using
-  Laravel 9


## Api
https://documenter.getpostman.com/view/11320596/Uz5CLd1y


## Database
- users
  - id
  - profile_media_id
  - name
  - email
  - role (admin, org_admin, staff, chef)
  - status
  - created_at
  - updated_at
    
- items
  - id
  - organization_id
  - thumbnail_media_id
  - name
  - description
  - created_at
  - updated_at
    
- organizations
  - id
  - logo_media_id
  - name
  - description
  - website
  - created_at
  - updated_at
    
- schedules
  - id
  - organization_id
  - item_ids
  - scheduled_at
  - created_at
  - updated_at
    
- items_selections
  - id
  - user_id
  - schedule_id
  - item_id
  - created_at
  - updated_at
  
- medias
  - id
  - resource_id
  - resource_type
  - media_type
  - filename
  - created_at
  - updated_at



