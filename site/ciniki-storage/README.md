This directory is used to store files and other large data for a business.  The directory structure
consists of business id hash, followed by module name, and then data.

The first directory in the hashing should be the first character from the uuid.  This will split things into
16 directories a-f0-9, and if there are 10,000 businesses, have approximately 625 per directory.

/ciniki-storage/[a-f0-9]/business-uuid/filedepot/[a-f0-9]/uuid-hash.extension
