#!/bin/bash
 
curl –silent –output output_filename -s "https://spreadit.io/s/programming/{new,controversial,hot,top}.php?&m=[1-20]" &
pidlist="$pidlist $!" 
curl –silent –output output_filename -s "https://spreadit.io/s/news/{new,controversial,hot,top}.php?&m=[1-20]" &
pidlist="$pidlist $!" 
curl –silent –output output_filename -s "https://spreadit.io/s/lounge/{new,controversial,hot,top}.php?&m=[1-20]" &
pidlist="$pidlist $!" 
curl –silent –output output_filename -s "https://spreadit.io/s/random/{new,controversial,hot,top}.php?&m=[1-20]" &
pidlist="$pidlist $!" 
curl –silent –output output_filename -s "https://spreadit.io/s/frogs/{new,controversial,hot,top}.php?&m=[1-20]" &
pidlist="$pidlist $!" 
curl –silent –output output_filename -s "https://spreadit.io/s/spreadit/{new,controversial,hot,top}.php?&m=[1-20]" &                                          
pidlist="$pidlist $!"  
 
for job in $pidlist do  
  echo $job     
  wait $job || let "FAIL+=1" 
done  
 
if [ "$FAIL" == "0" ]; then 
  echo "YAY!" 
else 
  echo "FAIL! ($FAIL)" 
fi

