<?php

namespace Middleware;

class Upload
{
      public static function file($file)
      {
            //if they DID upload a file...
            if($file['name'])
            {
                  //if no errors...
                  if(!$file['error'])
                  {
                        //now is the time to modify the future file name and validate the file
                        $image_type = explode('/', $file['type']);
                        $new_file_name = strtolower(uniqid().".".$image_type[1]); //rename file

                        if($file['size'] > (1024000)) //can't be larger than 1 MB
                        {
                              return 'File too large';
                        }
                        
                        //move it to where we want it to be
                        move_uploaded_file($file['tmp_name'], __PROJECT__.'/application/app/Teams/images/uploads/'.$new_file_name);
                        return $new_file_name;
                        
                  }
                  //if there is an error...
                  else
                  {
                        return 'there is an error';
                  }
            } else {
                  return 'file has no name';
            }
      }
}
