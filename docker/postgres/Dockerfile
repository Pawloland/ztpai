FROM postgres:17.2-bookworm

# Define the build argument
ARG POSTGRES_DB

RUN apt update && apt install -y ca-certificates && \
    yes | /usr/share/postgresql-common/pgdg/apt.postgresql.org.sh  &&\
    apt update &&\
    apt -y install  \
        postgresql-17-cron  \
        postgresql-server-dev-17 \
        libkrb5-dev \
        git \
        make \
        gcc

# https://stackoverflow.com/questions/78154884
# set up pg_cron and pldebugger
RUN echo "shared_preload_libraries = 'pg_cron,/usr/share/postgresql/17/contrib/pldebugger/plugin_debugger'" >> /usr/share/postgresql/postgresql.conf.sample
RUN echo "cron.database_name = '${POSTGRES_DB}'" >> /usr/share/postgresql/postgresql.conf.sample

# build pldebugger
ENV USE_PGXS=1
RUN cd /usr/share/postgresql/17/contrib && git clone https://github.com/EnterpriseDB/pldebugger.git && \
    cd pldebugger && \
    make && \
    make install


CMD ["postgres", "-c", "config_file=/usr/share/postgresql/postgresql.conf.sample"]

RUN mkdir -p /docker-entrypoint-initdb.d

COPY ./docker/postgres/db_init_plus_example_data.sql /docker-entrypoint-initdb.d/